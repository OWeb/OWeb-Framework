<?php


$this->addHeader('syntaxHighlighter/shCore.js', \OWeb\manage\Headers::javascript);
$this->addHeader('syntaxHighlighter/shBrushJScript.js', \OWeb\manage\Headers::js);
$this->addHeader('syntaxHighlighter/shBrushPhp.js', \OWeb\manage\Headers::js);
//$this->addHeader('syntaxHighlighter/shAutoloader.js', \OWeb\manage\Headers::javascript);

\OWeb\utils\js\jquery\HeaderOnReadyManager::getInstance()->add("SyntaxHighlighter.config.bloggerMode = true;
SyntaxHighlighter.defaults['toolbar'] = true;
SyntaxHighlighter.all();");

$this->addHeader('syntaxHighlighter/shCoreDefault.css', \OWeb\manage\Headers::css);

$this->form->display();

$accordion = $this->accordion;

$accordion->addSection('Section 1', '<p>Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
    ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
    amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
    odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.</p>');

$accordion->addSection('Section 2', '<p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
    purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
    velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
    suscipit faucibus urna.</p>');
		
$accordion->addSection('Section 3', ' <p>
    Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
    Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
    ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
    lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
    </p>
    <ul>
      <li>List item one</li>
      <li>List item two</li>
      <li>List item three</li>
    </ul>');

$accordion->addSection('Section 4', '<p>
    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
    mauris vel est.
    </p>
    <p>
    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
    inceptos himenaeos.
    </p>');



$accordion->display();
?>

<h1>Code used to generate the page</h1>

<h2>The Controller</h2>
<pre class="brush: php;">
class Accordion extends \OWeb\types\Controller{
	
	private $form;
	private $accordion;
	
	public function init() {
		$this->action_mode = self::ACTION_GET;
		
		//Applying special template
		$this->applyTemplateController(new \Controller\demo\Template());
		$this->addAction('refresh', 'doRefresh');
		
		//Creating the form
		$this->form = new \Controller\demo\jquery\ui\AccordionForm();
		$this->form->init();		
		$this->form->loadParams();
		
		//Creating the accordion
		$this->accordion = new \Controller\OWeb\widgets\jquery_ui\Accordion();
		$this->accordion->init();
	}
	
	/**
	 * If form returned an action
	 */
	public function doRefresh(){
		//Validating elements.  Should be already done but let's say on the safe side
		$this->form->validateElements();
		
		//If valid apply values to the accordion
		if($this->form->isValid()){
			foreach($this->form->getElements() as $element){
				$this->accordion->addParams($element->getName(), $element->getVal());
			}
		}
	}

	public function onDisplay() {
		$this->view->form = $this->form;
		$this->view->accordion = $this->accordion;
	}
}
</pre>

<h2>The View File</h2>

<pre class="brush: php;">
$this->form->display();

$accordion = $this->accordion;

$accordion->addSection('Section 1', '<p>Mauris mauris ante, blandit et, ultrices a, suscipit eget, quam. Integer
    ut neque. Vivamus nisi metus, molestie vel, gravida in, condimentum sit
    amet, nunc. Nam a nibh. Donec suscipit eros. Nam mi. Proin viverra leo ut
    odio. Curabitur malesuada. Vestibulum a velit eu ante scelerisque vulputate.</p>');

$accordion->addSection('Section 2', '<p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
    purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
    velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
    suscipit faucibus urna.</p>');
		
$accordion->addSection('Section 3', ' <p>
    Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
    Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
    ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
    lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
    </p>
    <ul>
      <li>List item one</li>
      <li>List item two</li>
      <li>List item three</li>
    </ul>');

$accordion->addSection('Section 4', '<p>
    Cras dictum. Pellentesque habitant morbi tristique senectus et netus
    et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
    faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
    mauris vel est.
    </p>
    <p>
    Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
    Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
    inceptos himenaeos.
    </p>');



$accordion->display();
?>
</pre>

<h2>The Form</h2>

<pre class="brush: php">	
class AccordionForm extends \Controller\OWeb\Helpers\Form\Form{
	//put your code here
	
	protected function registerElements() {
		$this->action_mode = self::ACTION_GET;
		
		$validatorBool = new \OWeb\utils\inputManagement\validators\Boolean();
		
		$jsString = new \OWeb\utils\inputManagement\validators\JsString();
		
		$this->setAction('refresh');
		
		$active = new \Controller\OWeb\Helpers\Form\Elements\Text();
		$active->init();
		$active->setName('active');
		$active->setTitle('Active');
		$active->setDescription("Which panel is currently open.");
		$active->addValidator(new \OWeb\utils\inputManagement\validators\Integer());
		$active->addValidator(new \OWeb\utils\inputManagement\validators\CanBeEmpty());
		$active->setVal(0);
		$this->addDisplayElement($active);
		
		$animate = new \Controller\OWeb\Helpers\Form\Elements\Text();
		$animate->init();
		$animate->setName('animate');
		$animate->setTitle('Animate');
		$animate->setVal('Animate');
		$animate->addValidator($jsString);
		$animate->setDescription("If and how to animate changing panels.");
		//$this->addDisplayElement($animate);
		
		$collapsible = new \Controller\OWeb\Helpers\Form\Elements\Radio();
		$collapsible->init();
		$collapsible->setName('collapsible');
		$collapsible->setTitle('Collapsible');
		$collapsible->add("true", "true");
		$collapsible->add("false", "false");
		$collapsible->addValidator($validatorBool);
		$collapsible->setVal('true');
		$collapsible->setDescription("hether all the sections can be closed at once. Allows collapsing the active section");
		$this->addDisplayElement($collapsible);
		
		$disabled = new \Controller\OWeb\Helpers\Form\Elements\Radio();
		$disabled->init();
		$disabled->setName('disabled');
		$disabled->setTitle('Disabled');
		$disabled->add("true", "true");
		$disabled->add("false", "false");
		$disabled->addValidator($validatorBool);
		$disabled->setVal('false');
		$disabled->setDescription("Disables the accordion if set to true.");
		$this->addDisplayElement($disabled);
		
		$event = new \Controller\OWeb\Helpers\Form\Elements\Text();
		$event->init();
		$event->setName('event');
		$event->setTitle('Event');
		$event->setVal('click');
		$event->addValidator($jsString);
		$event->setDescription("The event that accordion headers will react to in order to activate the associated panel. Multiple events can be specified, separated by a space.");
		$this->addDisplayElement($event);
		
		$heightStyle = new \Controller\OWeb\Helpers\Form\Elements\Select();
		$heightStyle->init();
		$heightStyle->setName('heightStyle');
		$heightStyle->setTitle('Height Style');
		$heightStyle->add("Auto", "auto");
		$heightStyle->add("Fill", "fill");
		$heightStyle->add("Content", "content");
		$heightStyle->addValidator($jsString);
		$heightStyle->setVal('auto');
		$heightStyle->setDescription("Controls the height of the accordion and each panel.");
		$this->addDisplayElement($heightStyle);
		
		$submit = new \Controller\OWeb\Helpers\Form\Elements\Submit();
		$submit->init();
		$submit->setVal("Refresh");
		$this->addDisplayElement($submit);
	}	
}
</pre>