<?php namespace LuisMayta\Youtube\Classes;

use \October\Rain\Support\Traits\Singleton;
use LuisMayta\Youtube\Models\Settings;

class YoutubeClient 
{
    use Singleton;

	private $obj;	
	private $key;
	private $channel;
	private $url;
    
    public function __construct()
	{
		$settings = Settings::instance();
		$this->key = $settings->api_key;
		$this->channel = $settings->channel_id;
		$this->url = "https://www.googleapis.com/youtube/v3/search?key=$this->key&channelId=$this->channel&part=snippet,id&order=date";
		$url = $this->url."&maxResults=9&type=video";
		$json = $this->file_get_contents_curl($url);
		$this->obj = json_decode($json, true);
	}
	public function getVideosPortada($cnt)
	{
		$url = $this->url."&maxResults=".$cnt."&type=video";
		$json = $this->file_get_contents_curl($url);
		$this->obj = json_decode($json, true);

		return (isset($this->obj['items'])) ? $this->obj['items'] : null;
	}
	public function getVideos()
	{
		return (isset($this->obj['items'])) ? $this->obj['items'] : null;
	}

	private function file_get_contents_curl($url) {

		$handle= curl_init();
		curl_setopt_array($handle, array(
      					CURLOPT_URL            => $url,
      					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_HEADER         => false
					)
		);

		$data = curl_exec($handle);
		
		$responseCode   = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		$downloadLength = curl_getinfo($handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
 
		if(curl_errno($handle))
		{
			return null;
		} else
		{
			//if($responseCode == "200") echo "successful request";
			curl_close($handle);
			return $data;
		}
	}
	
}

