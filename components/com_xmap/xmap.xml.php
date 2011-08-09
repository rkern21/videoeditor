<?php

/**
 * $Id: xmap.xml.php 140 2010-10-26 05:18:12Z guilleva $
 * $LastChangedDate: 2010-10-25 23:18:12 -0600 (Mon, 25 Oct 2010) $
 * $LastChangedBy: guilleva $
 * Xmap by Guillermo Vargas
 * A Sitemap component for Joomla! CMS (http://www.joomla.org)
 * Author Website: http://joomla.vargas.co.cr
 * Project License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

/** Wraps XML Sitemaps output */
class XmapXML extends Xmap
{

    var $_links;
    var $doCompression = 1;
    var $isNews = 0;
    var $sitename = '';

    function XmapXML(&$config, &$sitemap)
    {
        $this->view = 'xml';
        $this->uids = array();
        $app = JFactory::getApplication();
        $this->sitename = $app->getCfg('sitename');
        $this->language = preg_replace('/-.*/', '', $app->getCfg('language'));
        Xmap::Xmap($config, $sitemap);
    }

    /** Convert sitemap tree to a XML Sitemap list */
    function printNode(&$node)
    {
        // For news sitemaps only display news items
        if ($this->isNews && (!isset($node->newsItem) || !$node->newsItem)) {
            return true;
        }

        // For images sitemaps only display pages with images
        if ($this->isImages && (!isset($node->images) || !count($node->images))) {
            return true;
        }

        static $live_site, $len_live_site;
        if (!isset($live_site)) {
            $live_site = substr_replace(JURI::root(), "", -1, 1);
            $len_live_site = strlen($live_site);
        }

        $out = '';

        $link = Xmap::getItemLink($node);

        $is_extern = ( 0 != strcasecmp(substr($link, 0, $len_live_site), $live_site) );

        if (!isset($node->browserNav))
            $node->browserNav = 0;

        if ($node->browserNav != 3   // ignore "no link"
                && !$is_extern     // ignore external links
                && empty($this->_links[$link])) { // ignore links that have been added already
            $this->count++;
            $this->_links[$link] = 1;

            if (!isset($node->priority))
                $node->priority = "0.5";

            if (!isset($node->changefreq))
                $node->changefreq = 'daily';

            $changefreq = $this->sitemap->getProperty('changefreq', $node->changefreq, $node->id, 'xml', $node->uid);
            $priority = $this->sitemap->getProperty('priority', $node->priority, $node->id, 'xml', $node->uid);

            echo '<url>' . "\n";
            # Removed escapeURL until a better solution for UTF-8 is found
            # echo '<loc>', $this->escapeURL($link) ,'</loc>'."\n";
            echo '<loc>', $link, '</loc>' . "\n";
            $timestamp = (isset($node->modified) && $node->modified != FALSE && $node->modified != -1) ? $node->modified : time();
            $modified = gmdate('Y-m-d\TH:i:s\Z', $timestamp);
            if (!$this->isNews) {
                if ($this->_isAdmin) { //If the current user is admin
                    echo '<uid>', $node->uid, '</uid>' . "\n";
                    echo '<itemid>', $node->id, '</itemid>' . "\n";
                }
                if ($this->isImages) {
                    foreach ($node->images as $image) {
                        echo '<image:image>', "\n";
                        echo '<image:loc>', $image->src, '</image:loc>', "\n";
                        if ($image->title) {
                            $image->title = str_replace('&', '&amp;', html_entity_decode($image->title, ENT_NOQUOTES, 'UTF-8'));
                            echo '<image:title>', $image->title, '</image:title>', "\n";
                        } else {
                            echo '<image:title />';
                        }
                        if (isset($image->license) && $image->license) {
                            echo '<image:license>',str_replace('&', '&amp;',html_entity_decode($image->license, ENT_NOQUOTES, 'UTF-8')),'</image:license>',"\n";
                        }
                        echo '</image:image>', "\n";
                    }
                } else {
                    echo '<lastmod>', $modified, '</lastmod>' . "\n";
                    echo '<changefreq>', $changefreq, '</changefreq>' . "\n";
                    echo '<priority>', $priority, '</priority>' . "\n";
                }
            } else {
                if (isset($node->keywords)) {
                    # $keywords = str_replace(array('&amp;','&'),array('&','&amp;'),$node->keywords);
                    # $keywords = str_replace('&','&amp;',$node->keywords);
                    $keywords = htmlspecialchars($node->keywords);
                } else {
                    $keywords = '';
                }

                echo "<n:news>\n";
                echo " <n:publication>\n";
                echo "   <n:name>", htmlspecialchars($this->sitename), "</n:name>\n";
                echo "   <n:language>", htmlspecialchars($this->language), "</n:language>\n";
                echo " </n:publication>\n";

                echo '<n:publication_date>', $modified, '</n:publication_date>' . "\n";
                echo '<n:title>', htmlspecialchars($node->name), "</n:title>\n";
                if ($keywords) {
                    echo '<n:keywords>', $keywords, '</n:keywords>' . "\n";
                }
                echo "</n:news>\n";
            }
            echo '</url>', "\n";
        }
        return true;
    }

    function escapeURL($str)
    {
        static $xTrans;
        if (!isset($xTrans)) {
            $xTrans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
            foreach ($xTrans as $key => $value)
                $xTrans[$key] = '&#' . ord($key) . ';';
            // dont translate the '&' in case it is part of &xxx;
            $xTrans[chr(38)] = '&';
        }
        return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,4};)/", "&amp;", strtr($str, $xTrans));
    }

    function changeLevel($level)
    {
        return true;
    }

    function startOutput(&$menus, &$config)
    {
        $live_site = substr_replace(JURI::root(), "", -1, 1);

        $this->isNews = JRequest::getInt('news', 0);
        $this->isImages = JRequest::getInt('images', 0);

        $this->sendHeaders();
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        if (!$config->exclude_xsl && !$this->isNews) {
            $user = & JFactory::getUser();
            if ($user->get('gid') == "25") {
                echo '<?xml-stylesheet type="text/xsl" href="' . $live_site . '/index2.php?option=com_xmap&amp;view=xsladminfile&amp;tmpl=component"?>' . "\n";
            } else {
                echo '<?xml-stylesheet type="text/xsl" href="' . $live_site . '/index2.php?option=com_xmap&amp;view=xslfile&amp;tmpl=component"?>' . "\n";
            }
        }
        echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
        'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ' .
        'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ' .
        'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' .
        ($this->isImages ? ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"':'') .
        ($this->isNews ? ' xmlns:n="http://www.google.com/schemas/sitemap-news/0.9"' : '') .
        ">\n";
    }

    function sendHeaders()
    {
        // Don't compress something if the server is going todo it anyway. Waste of time.
        $this->doCompression = ($this->sitemap->compress_xml && !ini_get('zlib.output_compression') && ini_get('output_handler') != 'ob_gzhandler');

        @ob_end_clean();
        if ($this->doCompression) {
            $encoding = JResponse::_clientEncoding();
            header('Content-Encoding: ' . $encoding);
            header('X-Content-Encoded-By: Joomla! 1.5');
            ob_start();
        }
        header('Content-type: application/xml; charset=utf-8');

    }

    function endOutput(&$menus)
    {
        echo "</urlset>\n";
        if ($this->doCompression) {
            $data = ob_get_contents();
            @ob_end_clean();
            echo JResponse::_compress($data);
        }
    }

    function startMenu(&$menu)
    {
        return true;
    }

    function endMenu(&$menu)
    {
        return true;
    }

}
