<?php

/**
 *
 * Created by Phoenix404.
 * User: Phoenix404
 * Date: 09/09/2017
 * Start time: 17:30
 * End time: 22:00
 *
 */
namespace Useragent;

class UserAgent
{

    protected $userAgents           = array();
    public $userAgentsPath          = __DIR__.DIRECTORY_SEPARATOR."userAgents".DIRECTORY_SEPARATOR;

    /**
     * Return real useragent only if exists
     * @return string
     */
    public function getRealUserAgent()
    {
        $ua = "";
        if(isset($_SERVER['HTTP_USER_AGENT']))
            $ua = $_SERVER['HTTP_USER_AGENT'];
        return $ua;

    }

    /**
     * Load all user-agents file in userAgents attribute
     *
     * @return array
     */
    public function loadAllUserAgent()
    {
        $this->getJSONFiles();
        return $this->userAgents;
    }


    /**
     * Read all json file
     * @param string $specialPath
     * @return array
     * @throws \Exception
     */
    public function getJSONFiles($specialPath="")
    {
        $path = $this->userAgentsPath.ucfirst($specialPath);
        if(is_dir($path)) {
            $directories = glob($path . "/*");
            foreach ($directories as $directory) {
                if (is_dir($directory)) {
                    foreach (glob($directory . "/*.json") as $filename) {
                        $this->makeUserAgentsArray($filename);
                    }
                } else {
                    $this->makeUserAgentsArray($directory);
                }
            }
        }elseif(is_file($path)){
            $this->makeUserAgentsArray($path);
        }else{
            throw new \Exception("$path file doesn\'t exists!");
        }
        return $this->userAgents;
    }

    /**
     * Make or set the userAgents attribute in nice way
     * @param $file
     */
    public function makeUserAgentsArray($file)
    {
        $filename   = basename($file, ".json");
        $filename   = str_replace(["Browsers", "UserAgents"], "", $filename);
        $directory  = basename(dirname($file));
        $this->userAgents[$directory."_".$filename] = json_decode(file_get_contents($file));
    }

    /**
     * Load specific user-agent file from userAgents folder
     *
     * Current userAgents are divided into Devices, MobileOS, OS, Other
     * [Devices] you can find tablets or mobiles userAgents [Acer, AmazonKindle, GoogleNexus, HTC...] Go and check
     * [MobileOS] you can find mobile's operating systems [Android, Apple, Blackberry, iOS, ] Go and check
     * [OS] you can find Operating system's browsers UserAgents [Windows, Mac, Linux, Unix] Go and check
     * [Other] you can find different types userAgents like ConsoleBrowsers[like lynx], Spiders[google], GameConsole[PS*]
     *
     * To use this method you have to specify folder name[Devices, Mobi...]
     * If you want exactly specific then you add full file name or something like Device_GoogleNexus
     * Then type of userAgents[console or windows]
     *
     * @param $specific
     * @return mixed
     * @throws \Exception
     */
    public function loadSpecificUserAgent($specific)
    {

        if(!empty($this->userAgents) && isset($this->userAgents[$specific]))
            return $this->userAgents[$specific];

        if($this->str_contains($specific, "_"))
        {
            $folder = explode("_",$specific);
            $folder = ucfirst($folder[0]);
            $file   = ucfirst($folder[1]);
            $file   = $this->str_contains($file, "UserAgents")?$file:$file."UserAgents.json";
            return $this->getJSONFiles($folder.DIRECTORY_SEPARATOR.$file);
        }else{
            $method = "load".$specific."UserAgents";
            return $this->$method();
        }
    }

    /**
     * Load devices User agents
     *
     * @return array
     */
    public function loadDevicesUserAgents()
    {
        return $this->getJSONFiles("Devices");
    }

    /**
     * Load mobile [operating systems]->[browsers]->[userAgents]
     * @return array
     */
    public function loadMobileOSUserAgents()
    {
        return $this->getJSONFiles("MobileOS");
    }

    /**
     * Load Operating systems browsers user agents
     * @return array
     */
    public function loadOSUserAgents()
    {
        return $this->getJSONFiles("OS");
    }

    /**
     * Load Other("extra")
     * @return array
     */
    public function loadOtherUserAgents()
    {
        return $this->getJSONFiles("Other");
    }

    /**
     * Get all name|type|keys|whatever of user agents
     * @return array
     */
    public function getAllTypeUserAgents()
    {
        $userAgents = array();
        $directories = scandir($this->userAgentsPath);
        $this->loadAllUserAgent();
        $keys = array_keys($this->userAgents);
        foreach ($keys as $key){
            $key_   = $key;
            if($this->str_contains($key,$directories))
                 $key_ = str_replace($directories,"",$key);

            $userAgents[$key] = str_replace("_","",$key_);

        }
        return $userAgents;
    }

    /**
     * Get userAgents attribute's keys
     *
     * @param string $os
     * @return array|bool|false|int|string
     */
    public function getRandomUserAgentKey($os="")
    {
        if(strlen($os)>0) {
            $os = ucfirst($os);
            $keys = $this->getAllTypeUserAgents();
            switch ($os){
                case(in_array($os, $keys)):
                    return array_search($os, $keys);
                    break;
                case($this->str_contains($os, "_")):
                    $os = explode("_", $os);
                    if($this->str_contains(strtolower($os[0]), "os")) $os[0]  = str_replace(["Os","os"], "OS", $os[0]);
                    else $os[0]     = ucfirst($os[0]);

                    return $os[0]."_".ucfirst($os[1]);
                    break;
                case($this->str_contains($os, " ")):
                    $os     = str_replace(" ", "_", $os);
                    return $this->getRandomUserAgentKey($os);
                    break;
                default:
                    return false;
                    break;
            }

        }
        else{

            if(empty($this->userAgents))
                $this->loadAllUserAgent();

            $keys = array_keys($this->userAgents);
            $os   = $keys[rand(0,count($keys)-1)];
        }
        return $os;
    }

    /**
     * Get random object of file based on $data param
     * @param $data
     * @return mixed
     */
    public function getRandomUserAgentObj($data)
    {
        return json_decode(json_encode($data),1)[rand(0, count($data)-1)];
    }

    /**
     * Get random useragent property of ($this->)userAgent attribute
     * @param $data
     * @return mixed
     */
    public function getRandomUserAgentAttr($data)
    {
        return $this->getRandomUserAgentObj($data)["useragent"];
    }

    /**
     * return random useragent
     * @param string $os
     * @param string $expect
     * @param string $browser
     * @param bool $ifNoThenAny
     * @return mixed
     */
    public function getRandomUserAgent($os="", $expect="", $browser="", $ifNoThenAny=false)
    {
        $os = $this->getRandomUserAgentKey($os);
        if($os == false) $os = $this->getRandomUserAgentKey();

        while($os === $expect) $os = $this->getRandomUserAgentKey($os);
        
        if(strlen($browser)<=0)
            return $this->getRandomUserAgentAttr($this->userAgents[$os]);

        //found the browser in descriptions
        //first of all, we check if $os exists
        if(isset($this->userAgents[$os])) {
            $obj = ($this->searchInFiles("description", $browser, $this->userAgents[$os]));
            if (!empty($obj))
                return $this->getRandomUserAgentObj($obj)["useragent"];
        }
        //return any
        return ($ifNoThenAny)? $this->getRandomUserAgentAttr($this->userAgents[$os]):false;
    }

    /**
     * Find first match column values and exit
     * @param $column
     * @param $value
     * @param array $arr
     * @return array
     */
    public function findFirstInFiles($column, $value, $arr=[])
    {
        if(empty($arr)) {
            $this->loadAllUserAgent();
            $arr = $this->userAgents;
        }

        foreach($arr as $key =>$userAgent)
        {
            if(is_array($userAgent)) {
                $return = $this->searchInFiles($column, $value, $userAgent);
                if($return["success"])
                    return $return;
            }
            if(is_object($userAgent)) {
                $col = $userAgent->$column;
                if ($this->str_contains(strtolower($col), strtolower($value)))
                    return ["success" => true, "obj" => $userAgent];
            }
        }
        return ["success"=>false];
    }

    /**
     * Return all object based on $column and $value param
     * @param $column
     * @param $value
     * @param array $arr Reservations for recursion
     * @param array $returnValue Reservations for recursion
     * @return array
     */
    public function searchInFiles($column, $value, $arr=[], $returnValue=[])
    {
        if(empty($arr)) {
            $this->loadAllUserAgent();
            $arr = $this->userAgents;
        }
        foreach($arr as $key =>$userAgent)
        {

            if(is_array($userAgent)) {
                $returnValue = $this->searchInFiles($column, $value, $userAgent, $returnValue);
            }
            if(is_object($userAgent)) {
                if(property_exists($userAgent, $column)) {
                    $col = $userAgent->$column;
                    if ($this->str_contains(strtolower($col), strtolower($value)))
                        $returnValue[] = $userAgent;
                }
            }
        }
        return $returnValue;
    }

    /**
     * @param $browser
     * @return bool
     */
    public function findUserAgents($browser, $field="description")
    {
        $obj = ($this->searchInFiles($field, $browser));
        if (!empty($obj))
            return $this->getRandomUserAgentObj($obj)["useragent"];
        return false;
    }

    /**
     * @param $browser
     * @return bool
     */
    public function getBrowserUserAgent($browser, $field="description")
    {
        return $this->findUserAgents($browser, $field="description");
    }

    /**
     * Copy 
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    public function str_contains($haystack, $needles)
    {
        foreach ((array) $needles as $needle){
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

}