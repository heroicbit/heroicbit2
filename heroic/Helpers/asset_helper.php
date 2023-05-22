<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Minify the HTML text
 *
 * @param string $input
 * @return mixed
 *
 * @link    https://github.com/mecha-cms/mecha-cms/blob/master/engine/kernel/converter.php
 * @author  Taufik Nurrohman
 * @license GPL version 3 License Copyright
 *
 */
if ( ! function_exists('minifyHTML'))
{
	function minifyHTML($input)
	{
		if (trim($input) === "") {
			return $input;
		}
        // Remove extra white-space(s) between HTML attribute(s)
		$input = preg_replace_callback(
			'#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s',
			function ($matches) {
				return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
			},
			str_replace("\r", "", $input)
		);
        // Minify inline CSS declaration(s)
		if (strpos($input, ' style=') !== false) {
			$input = preg_replace_callback(
				'#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s',
				function ($matches) {
					return '<' . $matches[1] . ' style=' . $matches[2] . minifyCSS($matches[3]) . $matches[2];
				},
				$input
			);
		}
		return preg_replace(
			array(
                // t = text
                // o = tag open
                // c = tag close
                // Keep important white-space(s) after self-closing HTML tag(s)
				'#<(img|input)(>| .*?>)#s',
                // Remove a line break and two or more white-space(s) between tag(s)
				'#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
                '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
                '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
                '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
                '#<(img|input)(>| .*?>)<\/\1\x1A>#s', // reset previous fix
                '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
                // Force line-break with `&#10;` or `&#xa;`
                '#&\#(?:10|xa);#',
                // Force white-space with `&#32;` or `&#x20;`
                '#&\#(?:32|x20);#',
                // Remove HTML comment(s) except IE comment(s)
                '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
            ),
			array(
				"<$1$2</$1\x1A>",
				'$1$2$3',
				'$1$2$3',
				'$1$2$3$4$5',
				'$1$2$3$4$5$6$7',
				'$1$2$3',
				'<$1$2',
				'$1 ',
				"\n",
				' ',
				""
			),
			$input);
	}
}

/**
 * Minify the CSS text
 *
 * @param string $input
 * @return mixed
 *
 * @link    http://ideone.com/Q5USEF + improvement(s)
 * @author  Unknown, improved by Taufik Nurrohman
 * @license GPL version 3 License Copyright
 */
if ( ! function_exists('minifyCSS'))
{
    function minifyCSS($input)
    {
        if (trim($input) === "") {
            return $input;
        }
        // Force white-space(s) in `calc()`
        if (strpos($input, 'calc(') !== false) {
            $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#',
                function ($matches) {
                    return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
                },
                $input
            );
        }
        return preg_replace(
            array(
                // Remove comment(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
                // Remove unused white-space(s)
                '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
                // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
                '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
                // Replace `:0 0 0 0` with `:0`
                '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
                // Replace `background-position:0` with `background-position:0 0`
                '#(background-position):0(?=[;\}])#si',
                // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
                '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
                // Minify string value
                '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
                '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
                // Minify HEX color code
                '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
                // Replace `(border|outline):none` with `(border|outline):0`
                '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
                // Remove empty selector(s)
                '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
                '#\x1A#'
            ),
            array(
                '$1',
                '$1$2$3$4$5$6$7',
                '$1',
                ':0',
                '$1:0 0',
                '.$1',
                '$1$3',
                '$1$2$4$5',
                '$1$2$3',
                '$1:0',
                '$1$2',
                ' '
            ),
            $input
        );
    }
}

// JavaScript Minifier
function minifyJS($input)
{
    if (trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
        $input
    );
}

// Load asset file content
if(! function_exists('load_module_asset')){
	function load_module_asset($module, $file) 
	{
		if(! isset(ci()->config->config['modules'][$module]))
			return false;

		$fileinfo = pathinfo($file);
		$mime = get_mime_by_extension($file);


		$path = ci()->config->config['modules'][$module]['path'].'assets/'.$file;

		if (file_exists($path))
		{
			ci()->output->set_content_type($mime);
			ci()->output->set_output(file_get_contents($path));
		}
		else
		{
			ci()->output->set_status_header(404, 'File not found');
			echo 'File not found';
		}
	}
}

// Get module asset url
if(! function_exists('module_asset_url')){
	function module_asset_url($module, $file) {
		return site_url('asset/module/'.$module.'/'.$file);
	}
}