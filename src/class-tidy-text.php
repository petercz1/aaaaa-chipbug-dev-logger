<?php
namespace Chipbug\Tools\Logger;

defined('ABSPATH') or die('No script kiddies please!');

/**
 * tidies text for json and html
 */
class Tidy_Text
{
    private $options = array();
    private $trace = 'false';
    private $path = 'false';
    private $file_path;

    public function __construct()
    {
        $this->options = \file_get_contents(__DIR__ . '/serialized_options.txt');

        $this->options = unserialize($this->options);

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
    public function add_color_tag(string $details)
    {
        $terms = [
            '/#/',
            '/Call to undefined(.*?)/',
            '/method/',
            '/function/',
            '/syntax error/',
            '/does not have a method/',
            '/Class (.*?) not found/',
            '/Call to a member function (.*?) on null/',
            '/#[\s\S]*$/',
        ];
        $emphasised = [
            '<br>',
            '<span class="emphasised">Call to undefined</span> $1',
            '<span class="emphasised">method</span>',
            '<span class="emphasised">function</span>',
            '<span class="emphasised">syntax error</span>',
            '<span class="emphasised">does not have a method</span>',
            'Class <span class="emphasised">$1</span> not found',
            'Call to a <span class="emphasised">member function $1</span> on null',
            '<br>' . "$1",
        ];
        $details = \preg_replace($terms, $emphasised, $details);
        return $details;
    }

    /**
     * removes 'confusing' parts of error string
     * basically things like stack traces referring to WP core, long file paths etc
     * @param string $details
     * @return string cleaned details
     */
    public function remove_text(string $details)
    {
        //$path = ABSPATH;
        $terms = [
            "#$this->file_path#",
            //"/Stack trace:[\s\S]*$/",
            // '/Class(.*?)not found[\s\S]*$/',
            // '/Call to a member function (.*?) on null[\s\S]*$/',
            // '/Call to undefined(.*?) in[\s\S]*$/',
        ];
        $removed = [
            '',
            //"$this->trace",
            // 'Class $1 not found',
            // 'Call to a member function $1',
            // 'Call to undefined $1',
        ];
        $details = \preg_replace($terms, $removed, $details);
        return $details;
    }
}
