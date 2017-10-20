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
	protected $dom;

	/**
	 * @var  DOMElement
	 */
	protected $root;

	/**
	 * @var array
	 */
	protected $nodes = array();

	/**
	 * @param   string $file XML file
	 *
	 * @return  array|boolean
	 */
	public function load($file)
	{
		if (Vr360HelperFile::exists($file))
		{
			$this->dom = new DOMDocument;
			$this->dom->load($file);
			$this->root = $this->dom->documentElement;

			$this->nodes = $this->loadNodes($this->root);

			return $this->nodes;
		}

		return false;
	}

	/**
	 * @param   documentElement $node Node
	 *
	 * @return  array|string
	 */
	protected function loadNodes($node)
	{
		$nodes = array();

		switch ($node->nodeType)
		{
			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$nodes = trim($node->textContent);
				break;
			case XML_ELEMENT_NODE:

				foreach ($node->childNodes as $child)
				{
					$tmpNodes = $this->loadNodes($child);

					if (isset($child->tagName))
					{
						$tagName = $child->tagName;

						if (!isset($nodes[$tagName]))
						{
							$nodes[$tagName] = array();
						}

						$nodes[$tagName][] = $tmpNodes;
					}
					elseif ($tmpNodes || $tmpNodes === '0')
					{
						$nodes = (string) $tmpNodes;
					}
				}

				if ($node->attributes->length && !is_array($nodes))
				{
					// Has attributes but isn't an array
					// Change output into an array.
					$nodes = array('@content' => $nodes);
				}

				if (is_array($nodes))
				{
					if ($node->attributes->length)
					{
						$array = array();

						foreach ($node->attributes as $attrName => $attrNode)
						{
							$array[$attrName] = (string) $attrNode->value;
						}

						$nodes['@attributes'] = $array;
					}

					foreach ($nodes as $tag => $value)
					{
						if (is_array($value) && count($value) == 1 && $tag != '@attributes')
						{
							$nodes[$tag] = $value[0];
						}
					}
				}
				break;
		}

		return $nodes;
	}

	/**
	 * Get array of nodes
	 *
	 * @return array
	 */
	public function getNodes()
	{
		return $this->nodes;
	}

	/**
	 * Save back from array to XML
	 */
	public function flush()
	{

	}

	/**
	 * @return string
	 */
	public function toXml()
	{
		return $this->dom->saveXML();
	}
}
