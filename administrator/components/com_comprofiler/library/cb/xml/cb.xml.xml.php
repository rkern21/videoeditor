<?php
/**
* Fixing bugs and missing functions of PHP SimpleXMLElement in PHP < 5.1.3
* @version $Id: cb.xml.xml.php 1305 2010-11-25 22:28:28Z beat $
* @author Beat
* @copyright (C) 2007 Beat and Lightning MultiCom SA, 1009 Pully, Switzerland
* @license Lightning Proprietary. See licence. Allowed for free use within CB and for CB plugins.
*/

// Check to ensure this file is within the rest of the framework
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
 * Class to fix the bugs and shortcuts of PHP SimpleXMLElement
 *
 * @author Beat
 * @copyright Beat 2007
 * @licence allowed for free use within CB and for CB plugins
 */
class FixedSimpleXML extends SimpleXMLElement  {
	/**
	 * Get the name of the element.
	 * Warning: don't use getName() as it's broken up to php 5.2.3 included.
	 *
	 * @return string
	 */
	function name( ) {
		static $phpVersion524	=	null;
		if ( $phpVersion524 || ( ( $phpVersion524 === null ) && ( true === ( $phpVersion524 = ( 1 == version_compare( phpversion(), '5.2.3', '>' ) ) ) ) ) ) {
			return $this->getName();
		} else {
			return $this->aaa->getName();		// workaround php bug number 41867, fixed in 5.2.4
		}
	}

	/**
	 * Get the an attribute or all attributes of the element
	 *
	 * @param  string  $attribute  The name of the attribute if only one attribute is fetched
	 * @return mixed   string      If an attribute is given will return the attribute if it exist.
	 *                 boolean     Null if attribute is given but doesn't exist
	 * 				   array       If no attribute is given will return the complete attributes array
	 */
	function attributes( $attribute = null )
	{
		if( isset( $attribute ) ) {
			return ( isset( $this[$attribute]) ? (string) $this[$attribute] : null );
		}
		$array	=	array();
		foreach ( parent::attributes() as $k => $v ) {
			$array[$k]	=	(string) $v;
		}
		return $array;
	}

	/**
	 * Get the data of the element
	 *
	 * @access public
	 * @return string
	 */
	function data( ) {
		return (string) $this;
	}

	/**
	 * Adds an attribute to the element, override if it already exists
	 *
	 * @param string $name
	 * @param array  $attrs
	 */
	function addAttribute( $name, $value ) {
		$this[$name]	=	$value;					// it seems that php 5.1.6 requires htmlspecialchars() here to be happy, but stores the htmlspecialchars ! didn't find php changelog/bug report for that.
	}

	/**
	 * Get an element in the document by / separated path
	 * or FALSE
	 *
	 * @param	string	$path	The / separated path to the element
	 * @return	CBSimpleXMLElement or FALSE
	 */
	function & getElementByPath( $path ) {
		$false				=	false;
		$parts				=	explode( '/', trim($path, '/') );

		$tmp				=	$this;
		foreach ( $parts as $node ) {
			$found			=	false;
			foreach ( $tmp->children() as $child ) {
				if ( $child->name() == $node ) {
					$tmp	=	$child;
					$found	=	true;
					break;
				}
			}
			if ( ! $found ) {
				break;
			}
		}
		if ( $found ) {
			return $tmp;
		} else {
			return $false;
		}
	}

	/**
	 * Adds a direct child to the element
	 *
	 * @param string $name
	 * @param string $value
	 * @param string $nameSpace
	 * @param array  $attrs
	 * @param int 	 $level
	 * @return FixedSimpleXML the child				//BB added !
	 */
	function & addChildWithAttr( $name, $value, $nameSpace = null, $attrs = null, $level = null ) {
		if ( $attrs === null ) {
			$attrs		=	array();
		}
		$child			=&	parent::addChild( $name, htmlspecialchars( $value ), $nameSpace );
		foreach ( $attrs as $k => $v ) {
			$child->addAttribute( $k, $v );
		}
		return $child;
	}

	/**
	 * Removes a child
	 *
	 * @param FixedSimpleXML $child
	 */
	function removeChild( &$child ) {
		foreach ( $this->children() as $eachChild ) {
			if ( $eachChild == $child ) {
				unset( $eachChild );
			}
		}
		unset( $child );
	}

	/**
	 * Replace $this by $replacementXmlNode and its children in the XML tree, and extract $this from the tree
	 * @since 1.2.4
	 *
	 * @param  CBSimpleXMLElement  $replacementXmlNode
	 * @param  callback            $callBack to check/transform data or attributes of a node: $destinationData = function ( string|array $sourceData, CBSimpleXMLElement $sourceNode, CBSimpleXMLElement $destinationParentNode );
	 */
	function & replaceNodeAndChildren( &$replacementXmlNode, $callBack = null ) {
		$domNode				=	dom_import_simplexml( $this );
		$otherdomReplacement	=	dom_import_simplexml( $replacementXmlNode );
		$domReplacement			=	$domNode->ownerDocument->importNode( $otherdomReplacement );
		$domNode->parentNode->replaceChild( $domReplacement, $domNode );
		$this->_domCopyChildrenCallbackonNode( $domReplacement, $replacementXmlNode, $callBack );
		return $this;
	}
	/**
	 * Inserts $xmlNodeToInsert (and its children) as sibbling BEFORE $this in the XML tree, and returns the new XML node
	 * @since 1.2.4
	 *
	 * @param  CBSimpleXMLElement  $xmlNodeToInsert
	 * @param  callback            $callBack to check/transform data or attributes of a node: $destinationData = function ( string|array $sourceData, CBSimpleXMLElement $sourceNode, CBSimpleXMLElement $destinationParentNode );
	 * @return CBSimpleXMLElement  New node
	 */
	function & insertNodeAndChildrenBefore( &$xmlNodeToInsert, $callBack = null ) {
		$domNode				=	dom_import_simplexml( $this );
		$otherdomNodeToInsert	=	dom_import_simplexml( $xmlNodeToInsert );
		$domNodeToInsert		=	$domNode->ownerDocument->importNode( $otherdomNodeToInsert );
		$newNode				=	$domNode->parentNode->insertBefore( $domNodeToInsert, $domNode );
		$sibbling				=	$this->_domCopyChildrenCallbackonNode( $newNode, $xmlNodeToInsert, $callBack );
		return $sibbling;
	}
	/**
	 * Inserts $xmlNodeToInsert (and its children) as sibbling AFTER $this in the XML tree, and returns the new XML node
	 * @since 1.2.4
	 *
	 * @param  CBSimpleXMLElement  $xmlNodeToInsert
	 * @param  callback            $callBack to check/transform data or attributes of a node: $destinationData = function ( string|array $sourceData, CBSimpleXMLElement $sourceNode, CBSimpleXMLElement $destinationParentNode );
	 * @return CBSimpleXMLElement  New node
	 */
	function & insertNodeAndChildrenAfter( &$xmlNodeToInsert, $callBack = null ) {
		$domNode				=	dom_import_simplexml( $this );
		$otherdomNodeToInsert	=	dom_import_simplexml( $xmlNodeToInsert );
		$domNodeToInsert		=	$domNode->ownerDocument->importNode( $otherdomNodeToInsert );
		if( $domNode->nextSibling ) {
			$newNode			=	$domNode->parentNode->insertBefore( $domNodeToInsert, $domNode->nextSibling );
			$sibbling			=	$this->_domCopyChildrenCallbackonNode( $newNode, $xmlNodeToInsert, $callBack );
		} else {
			// $newNode			=	$domNode->parentNode->appendNode( $domNodeToInsert, $domNode->nextSibling );
			// SimpleXMLElement way which adds descendants too:
			$parent				=	$this->xpath( '..' );
			$sibbling			=	$parent[0]->addChildWithDescendants( $xmlNodeToInsert, $callBack );
		}
		return $sibbling;
	}
	/**
	 * Protected internal function that handles missing DOM functionality:
	 * $callBack to the copied attributes and data, as well as copying of children.
	 * @since 1.2.4
	 * @protected
	 *
	 * @param  DOMElement          $newNode         new DOM (incomplete) node just inserted/replaced
	 * @param  CBSimpleXMLElement  $xmlSourceNode   original XML node that got copied into DOM
	 * @param  callback            $callBack to check/transform data or attributes of a node: $destinationData = function ( string|array $sourceData, CBSimpleXMLElement $sourceNode, CBSimpleXMLElement $destinationParentNode );
	 * @return CBSimpleXMLElement  New XML node
	 */
	function & _domCopyChildrenCallbackonNode( &$newNode, $xmlSourceNode, $callBack ) {
		$newNodeXML				=&	simplexml_import_dom( $newNode, get_class( $this ) );
		if ( $callBack === null ) {
			$newNode->nodeValue	=	$xmlSourceNode->data();
			$attributesToCopy	=	$xmlSourceNode->attributes();
			foreach ( $attributesToCopy as $k => $v ) {
				// PHP 5.2 doesn't copy attributes however PHP 5.3 does it, so let's copy again for PHP 5.2:	//TBD later check from which version this 5.2 bug has been fixed...
				$newNode->setAttribute( $k, $attributesToCopy[$k] );
			}
		} else {
			$newNode->nodeValue	=	call_user_func_array( $callBack, array( $xmlSourceNode->data(), $xmlSourceNode, $newNodeXML ) );
			$copiedAttributes	=	$newNode->attributes;
			foreach ( $copiedAttributes as $k => $v ) {
				$newNode->removeAttribute( $k );
			}
			// the new set of $attributes can be different from old one, thus we needed to remove old set (copied in PHP 5.3 only) first, then copy new:
			$attributes			=	call_user_func_array( $callBack, array( $xmlSourceNode->attributes(), $xmlSourceNode, $newNodeXML ) );
			foreach ( $attributes as $k => $v ) {
				$newNode->setAttribute( $k, $v );
			}
		}
		foreach ($xmlSourceNode->children() as $child ) {
			$newNodeXML->addChildWithDescendants( $child, $callBack );
		}
		return $newNodeXML;
	}
}

?>
