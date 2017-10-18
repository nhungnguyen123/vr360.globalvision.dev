<?php

defined('_VR360_EXEC') or die;

/**
 * Class Vr360TourXml
 *
 * @since  2.0.0
 */
class Vr360TourXml
{
	/**
	 * @var  DOMDocument
	 */
	public $dom;

	protected $nodes = array();

	public function load($file)
	{
		if (Vr360HelperFile::exists($file))
		{
			$this->dom = new DOMDocument;
			$this->dom->load($file);

			$this->loadNodes($this->dom->documentElement, $this->nodes);
		}
	}

	protected function loadNodes($doc, &$nodes)
	{
		if ($doc->hasChildNodes())
		{
			foreach ($doc->childNodes as $node)
			{
				if (isset($node->nodeName))
				{
					$nodes[$node->nodeName]['atttributes'] = $this->getNodeAttributes($node);
					$nodes[$node->nodeName]['value']       = $node->nodeValue;
				}

				if ($node->hasChildNodes())
				{
					$this->loadNodes($node, $nodes[$node->nodeName]);
				}
			}
		}
	}

	public function getNodes()
	{
		return $this->nodes;
	}

	public function getInclude()
	{
		return $this->getNode('include');
	}

	protected function getNode($tag)
	{
		$nodesList = $this->dom->getElementsByTagName($tag);
		$nodes     = array();

		foreach ($nodesList as $index => $node)
		{
			$nodes[$index] = array();

			if ($node->hasAttributes())
			{
				$attributes = array();
				foreach ($node->attributes as $attribute)
				{
					$attributes[$attribute->nodeName] = $attribute->nodeValue;
				}
				$nodes[$index]['attributes'] = $attributes;
			}
		}

		return $nodes;
	}

	protected function getNodeAttributes($node)
	{
		$attributes = array();
		if ($node->hasAttributes())
		{

			foreach ($node->attributes as $attribute)
			{
				$attributes[$attribute->nodeName] = $attribute->nodeValue;
			}
		}

		return $attributes;
	}
}
