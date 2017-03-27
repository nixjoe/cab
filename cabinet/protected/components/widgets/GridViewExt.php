<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 02.11.13
 * Time: 5:31
 */

Yii::import('zii.widgets.grid.CGridView');

class GridViewExt extends CGridView {

    public $footerData;

    public function renderTableFooter() {
        if (!$this->footerData) {
            return parent::renderTableFooter();
        }

        echo "<tfoot>\n";
        echo "<tr class=\"footer-row\">\n";
        foreach($this->footerData as $data) {
            $attr = '';
            if (is_array($data)) {
                if (isset($data['colspan'])) {
                    $attr .= ' colspan="'.$data['colspan'].'"';
                }
                $val = $data['value'];
            } else {
                $val = $data;
            }

            echo sprintf('<td%s>', $attr);
            echo $val;
            echo '</td>';
        }
        echo "</tr>\n";
        echo "</tfoot>\n";
    }
} 