<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * tidies text for json and html
 */
class Tidy_Json_Text
{
    private $options = array();
    private $trace = 'false';
    private $path = 'false';
    private $file_path;

    public function __construct()
    {
        $this->options = \file_get_contents(plugin_dir_path(__DIR__) . 'install/serialized_options.json');

        $this->options = json_decode($this->options, true);

        if ('false' == $this->options['include_file_path']) {
			$this->file_path = $this->options['file_path'];
        }
        if ('true' == $this->options['include_trace']) {
            $this->trace = "$1";
        }
    }
    /**
     * adds inline <span class="emphasised"> to details string.
     * emphasises key phrases for json sent to browser
     * @param string $details
     * @return string cleaned details
     */
    // '/Call to undefined(.*?) in[\s\S]*$/',
    public function clean(string $details)
    {
        $terms = [
			'/#/',
			"~$this->file_path~",
			"/Stack trace:/",
            '/Call to undefined/',
            '/method/',
			'/function/',
            '/syntax error/',
            '/Class (.*?) not found/',
            // '/Call to a member function (.*?) on null/',
            // '/#[\s\S]*$/',
        ];
        $replaced_with = [
			'<br>',
			'',
			'<br>Stack trace:',
            '<span class="emphasised">Call to undefined</span>',
            '<span class="emphasised">method</span>',
            '<span class="emphasised">function</span>',
            '<span class="emphasised">syntax error</span>',
            'Class <span class="emphasised">$1</span> not found',
            // 'Call to a <span class="emphasised">member function $1</span> on null',
            // '<br>' . "$1",
        ];
        $details = \preg_replace($terms, $replaced_with, $details);
        return $details;
    }
}
