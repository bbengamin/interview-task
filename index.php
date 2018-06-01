<?php

class TableFactory
{

    const TABLE_SIZE = 3;
    const TABLE_WIDTH = 800;
    const TABLE_HEIGHT = 800;

    public function drawTable($input)
    {
        $table = $this->initTable(self::TABLE_SIZE);
        $table = $this->fillTableState($table, $input);

        echo $this->drawHTMLTable($table);
    }

    private function initTable($size)
    {
        $table = array();
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                $table[$i][$j] = true;
            }
        }
        return $table;
    }

    private function fillTableState($table, $input)
    {
        foreach ($input as $text) {
            $cells = explode(",", $text['cells']);
            $min = min($cells);

            foreach ($cells as $cell) {
                $i = ($cell - 1) / self::TABLE_SIZE;
                $j = ($cell - 1) % self::TABLE_SIZE;
                if ($cell != $min) {
                    $table[$i][$j] = false;
                } else {
                    $table[$i][$j] = $text;
                }
            }
        }
        return $table;
    }

    private function drawHTMLTable($table)
    {
        $html = "";

        $html .= "<table border='1' width='" . self::TABLE_WIDTH . "' height='" . self::TABLE_HEIGHT . "'>";
        for ($i = 0; $i < self::TABLE_SIZE; $i++) {
            $html .= "<tr>";
            for ($j = 0; $j < self::TABLE_SIZE; $j++) {
                $currentCell = $table[$i][$j];

                if (is_bool($currentCell) && $currentCell == true) {
                    $html .= "<td></td>";
                } else if (!is_bool($currentCell)) {
                    $cells = explode(",", $currentCell['cells']);

                    $colspan = $this->calculateColspan($cells);
                    $rowspan = $this->calculateRowspan($cells);

                    $color = $currentCell['color'];
                    $align = $currentCell['align'];
                    $valign = $currentCell['valign'];
                    $bgcolor = $currentCell['bgcolor'];
                    $text = $currentCell['text'];

                    $html .= "<td colspan='$colspan' rowspan='$rowspan' align='$align' valign='$valign' style='color:#$color;background-color: #$bgcolor;'>$text</td>";
                }
            }
            $html .= "</tr>";
        }
        $html .= "</table>";

        return $html;
    }

    private function calculateColspan($cells)
    {
        $min = min($cells);
        $max = max($cells);

        $a = (($min % self::TABLE_SIZE) != 0) ? ($min % self::TABLE_SIZE) : self::TABLE_SIZE;
        $b = (($max % self::TABLE_SIZE) != 0) ? ($max % self::TABLE_SIZE) : self::TABLE_SIZE;

        return ($b - $a) + 1;
    }

    private function calculateRowspan($cells)
    {
        $min = min($cells);
        $max = max($cells);

        $a = ($min - 1 >= 0) ? ($min - 1) / self::TABLE_SIZE : 0;
        $b = ($max - 1 >= 0) ? ($max - 1) / self::TABLE_SIZE : 0;

        return (intval($b) - intval($a)) + 1;
    }

}


$input = array(
    array(
        'text' => 'Текст красного цвета',
        'cells' => '2,5,8',
        'align' => 'center',
        'valign' => 'center',
        'color' => 'FF0000',
        'bgcolor' => '0000FF'
    ),
    array(
        'text' => 'Текст зеленого цвета',
        'cells' => '1,4',
        'align' => 'right',
        'valign' => 'bottom',
        'color' => '00FF00',
        'bgcolor' => 'FFFFFF'
    ),
    array(
        'text' => 'Текст черного цвета',
        'cells' => '6,9',
        'align' => 'right',
        'valign' => 'top',
        'color' => '000000',
        'bgcolor' => 'FFFFFF'
    )
);

$factory = new TableFactory();

$factory->drawTable($input);
?>