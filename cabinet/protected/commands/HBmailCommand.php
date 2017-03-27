<?php

class HBmailCommand extends CConsoleCommand{

    public function run($args) {
        set_time_limit(0);
        $file = dirname(__FILE__).'../../config/settings.php';

        $config = new SettingsForm();
        $config->load($file);

        if ($config->bd_sendType === SettingsForm::BD_NO_SEND) {
            exit('Off');
        }

        $criteria = new CDbCriteria();
        if ($config->bd_sendType === SettingsForm::BD_SEND_ONLY) {
            if (!$config->bd_countries) {
                exit('No countries');
            }
            $criteria->addInCondition('country', $config->bd_countries);
        } else if ($config->bd_countries){
            $criteria->addNotInCondition('country', $config->bd_countries);
        }

        $criteria->addCondition("DATE_FORMAT(birthDate,'%m-%d') = '" . date('m-d') . "'");

        $count = Users::model()->count($criteria);

        if (!$count) {
            exit('No users');
        }

        echo 'count: ', $count, "\n";
        if (!in_array('last', $args)) {
            $limit = ceil($count*25/100);
            $criteria->limit = $limit;
            echo 'limit: ', $limit, "\n";
        }

        $test = in_array('test', $args);

        $y = date('Y');
        $criteria->addCondition("(hb_year_sent IS NULL OR hb_year_sent != '".$y."')");
        $criteria->select = 't.*, lng.iso as lang_iso';
        $criteria->join = 'LEFT JOIN languages lng ON t.language = lng.id';

        $users = Users::model()->findAll($criteria);
        foreach($users as $user) {
            echo $user->email, ', ', $user->lang_iso ,"\n";
            $mail_params = array(
                'firstName' => $user->givenName,
                'middleName' => $user->middleName,
                'lastName' => $user->familyName,
                'language' => $user->lang_iso ? $user->lang_iso : 'en',
            );
            $to = $user->email;
            if ($test) {
                $to = 'info@fx-private.com';
            }
            if (Mail::send('birthday', $mail_params, $to)) {
                $user->setAttribute('hb_year_sent', $y);
                $user->save();
            }
        }
    }
} 