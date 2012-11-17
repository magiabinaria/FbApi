<?php
/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	*/
	App::import('Lib', 'Facebook.FB');
	
	class FacebookEvents extends AppEvents{
		public function onSetupCache() {
		}

		public function onSetupConfig(){
			return Configure::load('facebook.facebook');
		}

		private function __getConfig() {
			$config = Configure::read('Facebook');

			if(empty($config)) {
				Configure::load('facebook.facebook');
				$config = Configure::read('Facebook');
			}

			return $config;
		}

		public function onRequireComponentsToLoad(&$event){
			return 'Facebook.Connect';
		}

		public function onRequireHelpersToLoad(){
			return 'Facebook.Facebook';
		}
		
		public function onRequireTodoList(&$event){
			$config = Configure::read('Facebook');
			if(!empty($config)){
				return false;
			}
			
			return array(
				array(
					'name' => 'Api key is not setup',
					'type' => 'error',
					'url' => '#'
				)
			);
		}

		/**
		 * Called before cms content is echo'ed
		 */
		public function onCmsBeforeContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['Cms.before']) && in_array('like', $config['Cms.before'])) {
				$link = $data['_this']->Event->trigger('cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Facebook->like(
						array(
						'href' => Router::url(current($link['slugUrl']), true),
						'layout' => 'button_count',
						'title' => 'recommend'
						)
				);
			}
		}


		/**
		 * Called after cms content is echo'ed
		 */
		public function onCmsAfterContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['Cms.after']) && in_array('like', $config['Cms.after'])) {
				$link = $data['_this']->Event->trigger('cms.slugUrl', array('type' => 'contents', 'data' => $data['content']));
				return $data['_this']->Facebook->like(
						array(
						'href' => Router::url(current($link['slugUrl']), true),
						'layout' => 'button_count',
						'title' => 'recommend'
						)
				);
			}
		}

		/**
		 * Called before blog post is echo'ed
		 */
		public function onBlogBeforeContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['Blog.before']) && in_array('like', $config['Blog.before'])) {
				$link = $data['_this']->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Facebook->like(
						array(
						'href' => Router::url(current($link['slugUrl']), true),
						'layout' => 'button_count',
						'title' => 'recommend'
						)
				);
			}
		}

		/**
		 * Called after blog post is echo'ed
		 */
		public function onBlogAfterContentRender(&$event, $data) {
			$config = $this->__getConfig();
			if(isset($config['Blog.after']) && in_array('like', $config['Blog.after'])) {
				$link = $data['_this']->Event->trigger('blog.slugUrl', array('type' => 'posts', 'data' => $data['post']));
				return $data['_this']->Facebook->like(
						array(
						'href' => Router::url(current($link['slugUrl']), true),
						'layout' => 'button_count',
						'title' => 'recommend'
						)
				);
			}
		}

		/**
		 * called if a page is added and active, or activated.
		 */
		public function onCmsContentAdded(&$event, $data) {
			$link = $data['event']->trigger(
				'cms.slugUrl',
				array(
					'type' => 'contents',
					'data' => $data
				)
			);
			$link = Router::url(current($link['slugUrl']), true);

			$config = $this->__getConfig();


			$Facebook = new Facebook($config);
			$Facebook->api(
				'/'.$config['appId'].'/feed',
				'POST',
				array(
					'body' => $link,
					'description' => $event->Handler->params['data']['Content']['description'],
					'message' => $event->Handler->params['data']['Content']['description']
				)
			);
			exit;
		}
	}