<?php
require_once 'Smarty/Smarty.class.php';

$smarty = new Smarty();
$smarty->template_dir = $_SERVER['DOCUMENT_ROOT'].'/';
$smarty->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/templates_c/';
$smarty->cache_dir = $_SERVER['DOCUMENT_ROOT'].'/cache/';

// Use arrays as data source

$list = array(
	array(
		'id'	=> rand( 0, 1000),
		'name'	=> 'File',
		'child_list' => array(
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Open',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Save',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Close',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Exit',
				'child_list' => array(),
			)
		)
	),
	array(
		'id'	=> rand( 0, 1000),
		'name'	=> 'Edit',
		'child_list' => array(
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Cut',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Copy',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Paste',
				'child_list' => array(),
			),
			array(
				'id'	=> rand( 0, 1000),
				'name'	=> 'Delete',
				'child_list' => array(),
			)
		)
	),
	array(
		'id'	=> rand( 0, 1000),
		'name'	=> 'View',
		'child_list' => array()
	),
);

// Use classes as data source
 
class EmptyClass {}

class Node
{
	public $id;		// integer identifier
	public $name;	// Name of this node
	public $child_list;	// array of Tree or Node class

	function __construct( $name = '') {
		$this->id = rand( 0, 1000);
		$this->name = $name;
		$this->child_list = array();
	}

	function __destruct() {
		foreach ($this->child_list as $child)
			unset( $child);
	}

	function addChild( &$child) {
		$this->child_list[] = $child;
	}
} // class Node

class Tree extends Node {}

$tree = new Tree('Menu');
$node = array();
foreach (array('File','Edit','View') as $name)
	$tree->addChild( $node[$name] = new Node($name));
foreach (array('Open','Save','Close','Exit') as $name)
	$node['File']->addChild( new Node($name));
foreach (array('Cut','Copy','Paste','Delete') as $name)
	$node['Edit']->addChild( new Node($name));
	
// Use XML as data source

$xml = '<Tree>
	<id>0</id>
	<name>Menu</name>
	<child_list>
		<Node>
			<id>1</id>
			<name>File</name>
			<child_list>
				<Node>
					<id>11</id>
					<name>Open</name>
				</Node>
				<Node>
					<id>12</id>
					<name>Save</name>
				</Node>
				<Node>
					<id>13</id>
					<name>Close</name>
				</Node>
				<Node>
					<id>14</id>
					<name>Exit</name>
				</Node>
			</child_list>
		</Node>
		<Node>
			<id>2</id>
			<name>Edit</name>
			<child_list>
				<Node>
					<id>21</id>
					<name>Cut</name>
				</Node>
				<Node>
					<id>22</id>
					<name>Copy</name>
				</Node>
				<Node>
					<id>23</id>
					<name>Paste</name>
				</Node>
				<Node>
					<id>24</id>
					<name>Delete</name>
				</Node>
			</child_list>
		</Node>
		<Node>
			<id>3</id>
			<name>View</name>
		</Node>
	</child_list>
</Tree>';

// Assign all data and display

$time1 = microtime(true);
$smarty->assign( 'ARRAY_DATA', $list);
$smarty->assign( 'CLASS_DATA', $tree);
$smarty->assign( 'XML_DATA',   $xml);
$smarty->display( 'test.tpl');
$time2 = microtime(true);
print "<pre>Elapsed time: ".($time2-$time1)."</pre>\n";

?>
