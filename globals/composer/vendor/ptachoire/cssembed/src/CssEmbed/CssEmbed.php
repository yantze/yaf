<?php
/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *  08/08/12 15:22
 */

namespace CssEmbed;

/**
 * CssEmbed
 *
 * @author Pierre Tachoire <pierre.tachoire@gmail.com>
 */
class CssEmbed
{

    const SEARCH_PATTERN = "%url\\(['\" ]*((?!data:|//)[^'\"#\?: ]+)['\" ]*\\)%U";
    const URI_PATTERN = "url(data:%s;base64,%s)";

    protected $root_dir;

    /**
     * @param $root_dir
     */
    public function setRootDir($root_dir)
    {
        $this->root_dir = $root_dir;
    }

    /**
     * @param $css_file
     * @return null|string
     * @throws \InvalidArgumentException
     */
    public function embedCss($css_file)
    {
        $this->setRootDir(dirname($css_file));
        $return = null;
        $handle = fopen($css_file, "r");
        if ($handle === false) {
            throw new \InvalidArgumentException(sprintf('Cannot read file %s', $css_file));
        }
        while (($line = fgets($handle)) !== false) {
            $return .= $this->embedString($line);
        }
        fclose($handle);

        return $return;
    }

    /**
     * @param $content
     * @return mixed
     */
    public function embedString($content)
    {
        return preg_replace_callback(self::SEARCH_PATTERN, array($this, 'replace'), $content);
    }

    /**
     * @param $matches
     * @return string
     */
    protected function replace($matches)
    {
        return $this->embedFile($this->root_dir . DIRECTORY_SEPARATOR . $matches[1]);
    }

    /**
     * @param $file
     * @return string
     */
    protected function embedFile($file)
    {
        return sprintf(self::URI_PATTERN, $this->mimeType($file), $this->base64($file));
    }

    /**
     * @param $file
     * @return string
     */
    protected function mimeType($file)
    {
        if (function_exists('mime_content_type')) {
            return mime_content_type($file);
        }

        if ($info = @getimagesize($file)) {
            return($info['mime']);
        }

        return 'application/octet-stream';
    }

    /**
     * @param $file
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function base64($file)
    {
        if (is_file($file) == false || is_readable($file) == false) {
            throw new \InvalidArgumentException(sprintf('Cannot read file %s', $file));
        }

        return base64_encode(file_get_contents($file));
    }
}
