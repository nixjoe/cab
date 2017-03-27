<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 28.10.13
 * Time: 14:39
 */

class DateRangeSearchBehavior extends CActiveRecordBehavior {

    public $dateFromDefault = '1970-01-01 00:00:00';

    public $dateToDefault = '2099-12-31 00:00:00';

    public function dateRangeSearchCriteria($attribute, $value) {

        $criteria = new CDbCriteria();

        if (is_array($value)) {
            if (!empty($value[0]) || !empty($value[1])) {
                $dateFrom = $value[0];
                if (empty($dateFrom)) {
                    $dateFrom = $this->dateFromDefault;
                }

                $dateTo = $value[1];
                if (empty($dateTo)) {
                    $dateTo = $this->dateToDefault;
                }

                if ($dateFrom > $dateTo) {
                    list($dateFrom, $dateTo) = array($dateTo, $dateFrom);
                }

                $criteria->addBetweenCondition($attribute, $dateFrom, $dateTo);
            }
            else {
                $value = '';
                $criteria->compare($attribute, $value, true);
            }
        }
        else {
            $criteria->compare($attribute, $value, true);
        }

        return $criteria;
    }

}