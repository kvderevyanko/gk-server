<?php 

namespace app\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\base\InvalidParamException;
/**
 * This is just an example.
 */
class NavBar extends  \yii\base\Widget
{
	/**
	 * Brand the Navbar with two options: label and url
	*/
	public $brand = [];

	/**
	 * The actual navigation links
	 * Array with labe; and url
	*/
	public $links = [];

	/**
	 * Navigation Theme color
	*/
	public $theme;

    /**
     * @var string
     */
	public $title;


	public function init()
	{
		parent::init();

		if(!isset($this->brand['label']))
			$this->brand['label'] = $this->title;

		if(!isset($this->brand['url']))
			$this->brand['url'] = Yii::$app->homeUrl;

		if(empty($this->theme))
			$this->theme = 'default';
		else
			$this->theme = "ct-{$this->theme}";
	}

	
    public function run()
    {

    	\yii\bootstrap\NavBar::begin([
                'brandLabel' =>isset($this->brand['logo']) ? 
                        Html::img($this->brand['logo'], ['class'=>'pull-left img-responsive', 'style'=>'height:32px;padding-right:5px']).$this->brand['label'] :
                        $this->brand['label']    ,
                'brandUrl' => $this->brand['url'],
                'options' => [
                    'class' => "navbar navbar-{$this->theme} navbar-fixed",
                ],
                'innerContainerOptions'=>[
                	'tag'=>'div',
                	'class'=>'contrainer-fluid'
                ]
            ]);

    		echo Nav::widget([
    			'options' => ['class' => 'nav navbar-nav navbar-right'],
    			'items' => $this->links
    		]);

    	\yii\bootstrap\NavBar::end();
    }
}
