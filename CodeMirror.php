<?php
/*
 Plugin Name: CodeMirror for Wordpress
 Author: MATSUO Masaru (@localdisk)
 Author URI: http://d.hatena.ne.jp/localdisk/
 Plugin URI: http://d.hatena.ne.jp/localdisk/
 Description: Syntax highlighting Theme Editor
 Version: 1.0.0
 */
class CodeMirror {
    private $_version = '1.0.1';

    public function __construct() {
        $this->addAction();
    }

    public function addAction() {
        add_action('admin_print_scripts-theme-editor.php', array($this, 'scriptInsert'));
        add_action('admin_print_styles-theme-editor.php', array($this, 'styleInsert'));
        add_action('admin_print_scripts-plugin-editor.php', array($this, 'scriptInsert'));
        add_action('admin_print_styles-plugin-editor.php', array($this, 'styleInsert'));
        add_action('admin_footer', array($this, 'runCodeMirror'));
    }

    public function scriptInsert() {
        wp_enqueue_script('codemirror', plugins_url('js/codemirror.js', __FILE__), array(), $this->_version);
    }

    public function styleInsert() {
        wp_enqueue_style('codemirror', plugins_url('css/codemirror.css', __FILE__), array(), $this->_version);
    }

    public function runCodeMirror() {
        global $pagenow;
        if ($pagenow !== 'theme-editor.php' && $pagenow !== 'plugin-editor.php') return;
        $jsurl = plugins_url('', __FILE__) . '/js/';
        $cssurl = plugins_url('', __FILE__) . '/css/';
        $str = <<< EOF
<script type="text/javascript">
    var editor = CodeMirror.fromTextArea('newcontent', {
      height: "400px",
      parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js",
                   "tokenizephp.js", "parsephp.js",
                   "parsephphtmlmixed.js"],
      stylesheet: ["{$cssurl}xmlcolors.css", "{$cssurl}jscolors.css", "{$cssurl}csscolors.css", "{$cssurl}phpcolors.css"],
      path:"{$jsurl}",
      continuousScanning: 500
   });
</script>

EOF;
        _e($str);
    }
}
$codemirror = new CodeMirror();