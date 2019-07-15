<?php
namespace app\system\library;
/********************************************************************************************
 BaseRedis类，可以防止惊群现象发生(高并发时作用明显)

 防止惊群原理：
    1.伪造一个过期时间，比如为5分钟过期，所以fakeTime = time()+300
    2.真实过期时间，在上面伪造时间基础上多10分钟，也就是15分钟过期，realTime = fakeTime+600
    3.取数据的时候，先判断当前时间是否大于伪造时间：
    4.如果当前时间 time() < fakeTime ，则数据没过期，直接取缓存数据
    5.如果当前时间 fakeTime <= time() < realTime ，第一个人访问时，不设置锁，这种情况下，可以
      去数据库取数据更新缓存，其他人读取时加锁，这种情况下，只能读取旧缓存数据(因为真实时间未
      过，缓存仍有数据)，直到第一个人更新完缓存数据为止。这样就避免了全部人都穿透缓存去数据库
      读取数据而导致的惊群现象！

 配置参数写法：
    $config_redis = array(
        'host'=>'127.0.0.1',
        'auth'=>'123456',
        'port'=>'6379'
    );

 使用方式：
    $redis = Redis::getInstance(config_redis);
*********************************************************************************************/

class BaseRedis
{
    private $_redis;                         //redis对象
    public $_isConnectOk = false;             //是否连接成功，成功为true，失败为false
    private static $_instance  = [];    //本类实例
    private static $_cacheTime = 300;        //超时缓冲时间
    private $config;
    
    private function __construct($config = array()) {
        if (empty($config) || !is_array($config)) {
            trigger_error('redis配置参数不存在！');die(0);
        }

        $this->config = $config;

        try {  
            $this->_redis = new \Redis();
            if ($config['pconnect']) {
                $this->_isConnectOk = $this->_redis->pconnect($config['host'], $config['port']);
            } else {
                $this->_isConnectOk = $this->_redis->connect($config['host'], $config['port']);
            }

            if ($this->_isConnectOk && $config['auth']) {
                $this->_isConnectOk = $this->_redis->auth($config['auth']);
            }
        } catch (\Exception $e) {
            error_log('[' . date('Y-m-d H:i:s') . '] redis连接失败，请维护人员检查!');
        }
    }

    /*-------------------------------------------------------------------------------------- 
    | 私有化克隆机制
    --------------------------------------------------------------------------------------*/
    private function __clone() {}

    /*--------------------------------------------------------------------------------------
    | 获取redis单例
    |---------------------------------------------------------------------------------------
    | @param  array    $config
    |
    | @return object
    --------------------------------------------------------------------------------------*/
    public static function getInstance($config = [], $whichCache = 'master') {
        if(!isset(self::$_instance[$whichCache])){  
            self::$_instance[$whichCache] = new self($config);   
        }

        return self::$_instance[$whichCache];
    }

    /*------------------------------------------------------------------------------------------
    | 析构函数，删除redis长连接实例
    -------------------------------------------------------------------------------------------*/
    public function __destruct() {  
        $config = $this->config;
        if(isset($config['pconnect']) && $config['pconnect']){
            $this->_redis->close(); 
        } 
    } 

    /*-------------------------------------------------------------------------------------
    | 获得redis实例
    |--------------------------------------------------------------------------------------
    | @param $key   redis存的key/或随机值
    | @param string $tag   master/slave
    --------------------------------------------------------------------------------------*/
    public function getRedis() {
        return $this->_redis;
    }

    /*-------------------------------------------------------------------------------------
    | 获取值
    |--------------------------------------------------------------------------------------
    | @param  string    $key
    |
    | @return array,object,number,string,boolean
    |--------------------------------------------------------------------------------------
    | @desc 此方法使用了锁机制来防止防止缓存过期时所产生的惊群现象，
    |       保证只有一个进程获取数据，可以更新，其他进程仍然获取过期数据
    -------------------------------------------------------------------------------------*/
    public function getByLock($key) {
        $sth = $this->_redis->get($key);
        if ($sth === false) {
            return $sth;
        } else {
            $sth = json_decode($sth, TRUE);

            //在伪造期内失效了，但是在真实有效期内有效（只有$key . ".lock"===1为没加锁）
            //第一个人请求，$key . ".lock"===1，为没加锁，返回false，程序去数据库取数据
            //第二个人请求，$key . ".lock"===2，为加锁，直接取缓存旧数据
            //第三个人请求，和上面第二个人一样
            if (intval($sth['expire']) <= time() && time() < intval($sth['realExpire'])) {    
                $lock = $this->_redis->incr($key . ".lock");
                if ($lock === 1) {
                    return false;
                } else {
                    return $sth['data'];
                }
            } else if (intval($sth['realExpire']) <= time()) {
                return false;
            } else {
                return $sth['data'];
            }
        }
    }

    /*-------------------------------------------------------------------------------------------
    | 设置值
    |--------------------------------------------------------------------------------------------
    | @param  string    $key
    | @param  string,array,object,number,boolean    $value 缓存值
    | @param  int     $timeOut 过期时间，如果不设置，则使用默认时间，如果为 infinity 则为永久保存
    |
    | @return bool
    |--------------------------------------------------------------------------------------------
    | @desc 此方法会自动加入一些其他数据来避免惊群现象，如需保存原始数据，请使用 set
    -------------------------------------------------------------------------------------------*/
    public function setByLock($key, $value, $timeOut = null) {
        $expire = time();
        if (is_numeric($timeOut) && intval($timeOut) > 0) {
            $timeOut = intval($timeOut);
        } else {
            $timeOut = self::$_cacheTime;
        }

        //伪造超时时间
        $fakeExpireTime = time() + $timeOut;    

        //真实超时时间：在原有超时时间上累加缓冲时间得到，使程序有足够的时间生成缓存
        $realExpireTime = $timeOut + self::$_cacheTime;
        
        //制造数据
        $arg = ["data" => $value, "expire" => $fakeExpireTime, 'realExpire'=> $fakeExpireTime+self::$_cacheTime];

        //设置缓存
        $rs = $this->_redis->setex($key, $realExpireTime, json_encode($arg, TRUE));

        $this->_redis->delete($key . ".lock");

        return $rs;
    }

    /*-------------------------------------------------------------------------------------
    | 设置数据
    |--------------------------------------------------------------------------------------
    | @param string $key 键名
    | @param string $value 值
    | @param int $expire 过期时间
    -------------------------------------------------------------------------------------*/  
    public function set($key, $value, $expire = 0) {  
        if (is_int($expire) && $expire > 0) {
            return $this->_redis->setex($key, $expire, $value);
        }
        return $this->_redis->set($key, $value);
    }

    /*-------------------------------------------------------------------------------------
    | 获取数据
    |--------------------------------------------------------------------------------------
    | @param string $key 键名 
    -------------------------------------------------------------------------------------*/  
    public function get($key) {  
        return $this->_redis->get($key);
    }

    /*-------------------------------------------------------------------------------------
    | 批量获取数据 
    |--------------------------------------------------------------------------------------
    | @param string $keys 键数组 [field1, field2, field13]
    -------------------------------------------------------------------------------------*/  
    public function mget($keys) {  
        return $this->_redis->mget($keys); 
    }

    /*-------------------------------------------------------------------------------------
    | 返回 key 所储存的字符串值的长度
    | @param key
    | @return 字符串值的长度。当 key 不存在时，返回 0 。
    -------------------------------------------------------------------------------------*/
    public function strlen($key) {
        return $this->_redis->strlen($key); 
    }

    /*-------------------------------------------------------------------------------------
    | 获取所有符合要求的键值 
    |--------------------------------------------------------------------------------------
    | @param string $key KEY名称 
    -------------------------------------------------------------------------------------*/  
    public function keys($key) {  
        return $this->_redis->keys($key);  
    }

    /*-------------------------------------------------------------------------------------
    | 判断key是否存在
    | @param string $key KEY名称
    | @return 若key存在，返回 1 ，否则返回 0 。
    -------------------------------------------------------------------------------------*/
    public function exists($key) {
        return $this->_redis->exists($key);
    }

    /*-------------------------------------------------------------------------------------
    | key设置到期时间 单位：s
    | 注：该方法设置的是到期的时间，另有expire设置的是生存时间（秒）
    | @param string $key KEY名称
    | @param int $timestamp KEY的过期时间，unix 时间戳
    | @return 如果生存时间设置成功，返回 1 。当 key 不存在或没办法设置生存时间，返回 0 。
    -------------------------------------------------------------------------------------*/
    public function expireAt($key, $timestamp) {
        return $this->_redis->expireAt($key, $timestamp);
    }

    /*-------------------------------------------------------------------------------------
    | key设置到期时间 单位：ms
    | 注：该方法设置的是到期的时间，另有pexpire设置的是生存时间（毫秒）
    | @param string $key KEY名称
    | @param int $millitimestamp KEY的过期时间，毫秒时间
    | @return 如果生存时间设置成功，返回 1 。当 key 不存在或没办法设置生存时间，返回 0 。
    -------------------------------------------------------------------------------------*/
    public function pexpireAt($key, $millitimestamp) {
        return $this->_redis->pexpireAt($key, $millitimestamp);
    }

    /*-------------------------------------------------------------------------------------
    | 获取 key 的剩余生存时间
    | @param string $key KEY名称
    | @return 当 key 不存在时，返回 -2;
    |         当 key 存在但没有设置剩余生存时间时，返回 -1;
    |         否则，以秒为单位，返回 key 的剩余生存时间;
    ---------------------------------------------------------------------------------------*/
    public function ttl($key) {
        return $this->ttl($key);
    }

    /*-------------------------------------------------------------------------------------
    | 删除一条数据 
    |--------------------------------------------------------------------------------------
    | @param string $key KEY名称 
    -------------------------------------------------------------------------------------*/  
    public function delete($key) {  
        return $this->_redis->delete($key);  
    }

    /*-------------------------------------------------------------------------------------
    | 返回key的列表长度 
    |--------------------------------------------------------------------------------------
    | @param string $key KEY名称 
    -------------------------------------------------------------------------------------*/ 
    public function lLen($key) {
        return $this->_redis->lLen($key); 
    }

    /*-------------------------------------------------------------------------------------
    | 数据入队列
    | @param string $key KEY名称
    | @param string|array $value 获取得到的数据
    | @param bool $right 是否从右边开始入
    | @return 执行 RPUSH 操作后，表的长度
    -------------------------------------------------------------------------------------*/
    public function push($key, $value, $left = false) {
        return $left ? $this->_redis->lPush($key, $value) : $this->_redis->rPush($key, $value);
    }

    /*-------------------------------------------------------------------------------------
    | 数据出队列
    | @param string $key KEY名称
    | @param bool $left 是否从左边开始出数据
    | @return 列表的元素。当 key 不存在时，返回 false。
    -------------------------------------------------------------------------------------*/
    public function pop($key, $left = true) {
        return $left ? $this->_redis->lPop($key) : $this->_redis->rPop($key);
    }

    /*-------------------------------------------------------------------------------------
    | 数据自增
    | @param string $key KEY名称
    | @param int $increment 加量 default=1
    | @return 加上 increment 之后， key 的值
    -------------------------------------------------------------------------------------*/
    public function incrBy($key, $increment = 1) {
        return $this->_redis->incrby($key, $increment);
    }

    /*-------------------------------------------------------------------------------------
    | 数据自减
    | @param string $key KEY名称
    | @param  int $decrement 减量 default=1
    | @return 减去 decrement 之后， key 的值。
    --------------------------------------------------------------------------------------*/
    public function decrBy($key, $decrement = 1) {
        return $this->_redis->decrby($key, $decrement);
    }

    /*-------------------------------------------------------------------------------------
    | hash设置数据 (不设置过期时间)
    |--------------------------------------------------------------------------------------
    | @param string $hashKey key值
    | @param string $kvData  键值数组 ['field1' => 1, 'field2' => 2, 'field13' => 3]
    -------------------------------------------------------------------------------------*/  
    public function hmset($hashKey, $kvData) {  
        return $this->_redis->hmset($hashKey, $kvData);  
    }

    /*-------------------------------------------------------------------------------------
    | 批量获取数据 
    |--------------------------------------------------------------------------------------
    | @param string $key key值
    | @param string $fields [field1, field2, field13]
    -------------------------------------------------------------------------------------*/  
    public function hmget($key, $fields) {  
        return $this->_redis->hmget($key, $fields);  
    }

    /*-------------------------------------------------------------------------------------
    | hash设置数据 (不设置过期时间)
    |--------------------------------------------------------------------------------------
    | @param string $hashKey key值
    | @param string $field 字段
    | @param string $value 值
    -------------------------------------------------------------------------------------*/  
    public function hset($hashKey, $field, $value) {  
        return $this->_redis->hset($hashKey, $field, $value);  
    }

    /*-------------------------------------------------------------------------------------
    | 获取一个hash的key数据
    |--------------------------------------------------------------------------------------
    | @param string $hashKey key值
    | @param string $field 字段
    -------------------------------------------------------------------------------------*/  
    public function hget($hashKey, $field) { 
        return $this->_redis->hget($hashKey, $field); 
    }

    /*-------------------------------------------------------------------------------------
    | 获取一个hash的key数据
    |--------------------------------------------------------------------------------------
    | @param string $hashKey key值
    | @param string $key 键名
    -------------------------------------------------------------------------------------*/  
    public function hgetall($hashKey) { 
        return $this->_redis->hgetall($hashKey); 
    }

    /*------------------------------------------------------------------------------------
    | 返回名称为hk的hash中元素个数
    ------------------------------------------------------------------------------------*/
    public function hLen($hkey) {
        return $this->_redis->hLen($hkey);
    }

    /*------------------------------------------------------------------------------------
    | 名称为h的hash中是否存在键名字为member的域
    | @param $hkey
    | @param $member
    | @return 如果哈希表含有给定域，返回 1 。如果哈希表不含有给定域，或 key 不存在，返回 0 。
    ------------------------------------------------------------------------------------*/
    public function hExists($hkey, $member) {
        return $this->_redis->hExists($hkey, $member);
    }

    /*------------------------------------------------------------------------------------
    | 名称为hkey的hash中是在键名$member的值加（减）$value
    | @param $hkey
    | @param $member
    | @param $value   可以是负数
    | @return 执行 HINCRBY 命令之后，哈希表 key 中域 field 的值。
    ------------------------------------------------------------------------------------*/
    public function hIncrBy($hkey, $member, $value) {
        return $this->_redis->hIncrBy($hkey, $member, $value);
    }

    /*-----------------------------------------------------------------------------------
    | 删除hash数据，删除hkey下 member 键值
    | 当member为空时删除整个hk数据,请使用delete方法
    -----------------------------------------------------------------------------------*/
    public function hDel($hkey, $member = '') {
        return $this->_redis->hDel($hkey, $member);
    }

    /*-----------------------------------------------------------------------------------
    | 向集合中添加一个值
    |
    | @return boolean true | false
    -----------------------------------------------------------------------------------*/
    public function sAdd($key, $value) {
        return $this->_redis->sAdd($key, $value);
    }

    /*-----------------------------------------------------------------------------------
    | 从指定集合中移除一个值
    |
    | @return boolean true | false
    ------------------------------------------------------------------------------------*/
    public function sRemove($key, $value) {
        return $this->_redis->sRem($key, $value);
    }

    /*-----------------------------------------------------------------------------------
    | 返回集合的成员数量
    ------------------------------------------------------------------------------------*/
    public function sCard($key) {
        return $this->_redis->sCard($key);
    }

    /*-----------------------------------------------------------------------------------
    | 检擦一个值是否存在于集合中
    |
    | @return boolean true | false
    -----------------------------------------------------------------------------------*/
    public function sIsMember($key, $value) {
        return $this->_redis->sIsMember($key, $value);
    }

    /*-----------------------------------------------------------------------------------
    | 返回集合内的所有元素
    -----------------------------------------------------------------------------------*/
    public function sMembers($key) {
        return $this->_redis->sMembers($key);
    }

    /*-----------------------------------------------------------------------------------
    | 按条件取得数据
    | 参数：
    | [
    |     'by'    => 'pattern',        --匹配模式
    |     'limit' => array(0, 1),      --条数限制
    |     'get'   => 'pattern'         --要获取的数据
    |     'sort'  => 'asc' or 'desc',  --排序
    |     'alpha' => TRUE,             --是否是按照字母排序
    |     'store' => 'external-key'    --是否保存排序后的结果，保存结果的key
    | ]
    ----------------------------------------------------------------------------------*/
    public function sort($key, $where = []){
        return $this->_redis->sort($key, $where);
    }

    /*-----------------------------------------------------------------------------------
    | 为有序集添加一个成员
    ------------------------------------------------------------------------------------*/
    public function zAdd($key, $score, $value) {
        return $this->_redis->zAdd($key, $score, $value);
    }

    /*-----------------------------------------------------------------------------------
    | 为有序集 key 的成员 member 的 score 值加上增量 increment(用户排序)
    | @param $key
    | @param $value
    | @param $member
    | @return member 成员的新 score 值，以字符串形式表示
    ----------------------------------------------------------------------------------*/
    public function zIncrBy($key, $value, $member) {
        return $this->_redis->zIncrBy($key, $value, $member);
    }

    /*-----------------------------------------------------------------------------------
    | 返回有序集 key 的基数
    ------------------------------------------------------------------------------------*/
    public function zCard($key) {
        return $this->_redis->zCard($key);
    }

    /*-----------------------------------------------------------------------------------
    | 返回有序集合中的某成员的成绩
    ------------------------------------------------------------------------------------*/
    public function zScore($key, $member) {
        return $this->_redis->zScore($key, $member);
    }

    /*-----------------------------------------------------------------------------------
    | 删除有序集合中的指定成员
    ------------------------------------------------------------------------------------*/
    public function zDelete($key, $member) {
        return $this->_redis->zDelete($key, $member);
    }

    /*-----------------------------------------------------------------------------------
    | 删除有序集合中的成绩介于$start和$end之间的成员个数
    ------------------------------------------------------------------------------------*/
    public function zCount($key, $start, $end) {
        return $this->_redis->zCount($key, $start, $end);
    }

    /*-----------------------------------------------------------------------------------
    | （升序）取得特定范围内的排序元素,0代表第一个元素,1代表第二个以此类推。-1代表最后一个,-2代表倒数第二个...
    | $redis->zRange('key1', 0, -1, true);
    ------------------------------------------------------------------------------------*/
    public function zRange($key, $start, $end, $withscores = false) {
        return $this->_redis->zRange($key, $start, $end, $withscores);
    }

    /*-----------------------------------------------------------------------------------
    | （升序）返回key对应的有序集合中score介于min和max之间的所有元素（包哈score等于min或者max的元素）。
    | 元素按照score从低到高的顺序排列。如果元素具有相同的score，那么会按照字典顺序排列。
    | @param $key
    | @param $start
    | @param $stop
    | @param options ['withscores' => TRUE, 'limit' => [1, 1]]
    | @return 指定区间内，带有 score 值(可选)的有序集成员的升序列表
    -----------------------------------------------------------------------------------*/
    public function zRangeByScore($key, $start, $end, $options = []) {
        return $this->_redis->zRangeByScore($key, $start, $end, $options);
    }

    /*-----------------------------------------------------------------------------------
    | （倒序）返回有序集 key 中，指定区间内的成员(用户倒序排序)
    | @param $key
    | @param $start
    | @param $stop
    | @param bool $withscore  
    | @return 指定区间内，带有 score 值(可选)的有序集成员的列表
    -----------------------------------------------------------------------------------*/
    public function zRevRange($key, $start, $stop, $withscore = false) {
        return $this->_redis->zRevRange($key, $start, $stop, $withscore);
    }

    /*-----------------------------------------------------------------------------------
    | （倒序）返回key对应的有序集合中score介于min和max之间的所有元素（包哈score等于min或者max的元素）。
    | 元素按照score从低到高的顺序排列。如果元素具有相同的score，那么会按照字典顺序排列。
    | @param $key
    | @param $start
    | @param $stop
    | @param options ['withscores' => TRUE, 'limit' => [1, 1]]
    | @return 指定区间内，带有 score 值(可选)的有序集成员的倒序列表
    -----------------------------------------------------------------------------------*/
    public function zRevRangeByScore($key, $start, $end, $options = []) {
        return $this->_redis->zRevRangeByScore($key, $start, $end, $options);
    }

    /*-----------------------------------------------------------------------------------
    | （升序）返回key对应的有序集合中member元素的索引值
    | $redis->zAdd('key', 1, 'one');
    | $redis->zAdd('key', 2, 'two');
    | $redis->zRank('key', 'one');  0 
    | $redis->zRank('key', 'two');  1 
    -----------------------------------------------------------------------------------*/
    public function zRank($key, $member) {
        return $this->_redis->zRank($key, $member);
    }

    /*-----------------------------------------------------------------------------------
    | （倒序）返回key对应的有序集合中member元素的索引值
    | $redis->zAdd('key', 1, 'one');
    | $redis->zAdd('key', 2, 'two');
    | $redis->zRevRank('key', 'one');  1 
    | $redis->zRevRank('key', 'two');  0 
    -----------------------------------------------------------------------------------*/
    public function zRevRank($key, $member) {
        return $this->_redis->zRevRank($key, $member);
    }

    /*-------------------------------------------------------------------------------------
    | 手动持久化数据到硬盘（单独起一个线程）
    -------------------------------------------------------------------------------------*/
    public function bgSave() {
        return $this->_redis->bgSave();
    }

    /*-------------------------------------------------------------------------------------
    | @return  返回关于 Redis 服务器的各种信息和统计数值
    -------------------------------------------------------------------------------------*/
    public function redisInfo() {
        return $this->_redis->info();
    }

    /*-------------------------------------------------------------------------------------
    | 清空数据 
    -------------------------------------------------------------------------------------*/ 
    public function flushAll() {  
        return $this->_redis->flushAll();  
    } 

}
