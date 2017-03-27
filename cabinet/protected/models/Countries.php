<?php

/**
 * This is the model class for table "countries".
 *
 * The followings are the available columns in table 'countries':
 * @property integer $isoID
 * @property string $iso2
 * @property string $iso3
 * @property string $dialcode
 * @property integer $disabled
 * @property string $mul
 * @property string $bul
 * @property string $bul_alt
 * @property string $cat
 * @property string $cat_alt
 * @property string $ces
 * @property string $ces_alt
 * @property string $dan
 * @property string $dan_alt
 * @property string $deu
 * @property string $deu_alt
 * @property string $ell
 * @property string $ell_alt
 * @property string $eng
 * @property string $eng_alt
 * @property string $epo
 * @property string $epo_alt
 * @property string $est
 * @property string $est_alt
 * @property string $fin
 * @property string $fin_alt
 * @property string $fra
 * @property string $fra_alt
 * @property string $hrv
 * @property string $hrv_alt
 * @property string $hun
 * @property string $hun_alt
 * @property string $ind
 * @property string $ind_alt
 * @property string $isl
 * @property string $isl_alt
 * @property string $ita
 * @property string $ita_alt
 * @property string $lav
 * @property string $lav_alt
 * @property string $lit
 * @property string $lit_alt
 * @property string $ndl
 * @property string $ndl_alt
 * @property string $nno
 * @property string $nno_alt
 * @property string $nob
 * @property string $nob_alt
 * @property string $pol
 * @property string $pol_alt
 * @property string $por
 * @property string $por_alt
 * @property string $ron
 * @property string $ron_alt
 * @property string $rus
 * @property string $rus_alt
 * @property string $slk
 * @property string $slk_alt
 * @property string $slv
 * @property string $slv_alt
 * @property string $spa
 * @property string $spa_alt
 * @property string $srp
 * @property string $srp_alt
 * @property string $swe
 * @property string $swe_alt
 * @property string $tur
 * @property string $tur_alt
 * @property string $ukr
 * @property string $ukr_alt
 * @property integer $curID
 *
 * The followings are the available model relations:
 * @property Currencies $cur
 * @property Users[] $users
 */
class Countries extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Countries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'countries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('curID', 'required'),
			array('isoID, disabled, curID', 'numerical', 'integerOnly'=>true),
			array('iso2', 'length', 'max'=>2),
			array('iso3', 'length', 'max'=>3),
			array('dialcode', 'length', 'max'=>16),
			array('mul', 'length', 'max'=>44),
			array('bul, bul_alt, cat, cat_alt, ces, ces_alt, dan, dan_alt, deu, deu_alt, ell, ell_alt, eng, eng_alt, epo, epo_alt, est, est_alt, fin, fin_alt, fra, fra_alt, hrv, hrv_alt, hun, hun_alt, ind, ind_alt, isl, isl_alt, ita, ita_alt, lav, lav_alt, lit, lit_alt, ndl, ndl_alt, nno, nno_alt, nob, nob_alt, pol, pol_alt, por, por_alt, ron, ron_alt, rus, rus_alt, slk, slk_alt, slv, slv_alt, spa, spa_alt, srp, srp_alt, swe, swe_alt, tur, tur_alt, ukr, ukr_alt', 'length', 'max'=>256),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('isoID, iso2, iso3, dialcode, disabled, mul, bul, bul_alt, cat, cat_alt, ces, ces_alt, dan, dan_alt, deu, deu_alt, ell, ell_alt, eng, eng_alt, epo, epo_alt, est, est_alt, fin, fin_alt, fra, fra_alt, hrv, hrv_alt, hun, hun_alt, ind, ind_alt, isl, isl_alt, ita, ita_alt, lav, lav_alt, lit, lit_alt, ndl, ndl_alt, nno, nno_alt, nob, nob_alt, pol, pol_alt, por, por_alt, ron, ron_alt, rus, rus_alt, slk, slk_alt, slv, slv_alt, spa, spa_alt, srp, srp_alt, swe, swe_alt, tur, tur_alt, ukr, ukr_alt, curID', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'currency_' => array(self::BELONGS_TO, 'Currencies', 'curID'),
			'users_' => array(self::HAS_MANY, 'Users', 'country'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'isoID' => 'Iso',
			'iso2' => 'Iso2',
			'iso3' => 'Iso3',
			'dialcode' => 'Dialcode',
			'disabled' => 'Disabled',
			'mul' => 'Mul',
			'bul' => 'Bul',
			'bul_alt' => 'Bul Alt',
			'cat' => 'Cat',
			'cat_alt' => 'Cat Alt',
			'ces' => 'Ces',
			'ces_alt' => 'Ces Alt',
			'dan' => 'Dan',
			'dan_alt' => 'Dan Alt',
			'deu' => 'Deu',
			'deu_alt' => 'Deu Alt',
			'ell' => 'Ell',
			'ell_alt' => 'Ell Alt',
			'eng' => 'Eng',
			'eng_alt' => 'Eng Alt',
			'epo' => 'Epo',
			'epo_alt' => 'Epo Alt',
			'est' => 'Est',
			'est_alt' => 'Est Alt',
			'fin' => 'Fin',
			'fin_alt' => 'Fin Alt',
			'fra' => 'Fra',
			'fra_alt' => 'Fra Alt',
			'hrv' => 'Hrv',
			'hrv_alt' => 'Hrv Alt',
			'hun' => 'Hun',
			'hun_alt' => 'Hun Alt',
			'ind' => 'Ind',
			'ind_alt' => 'Ind Alt',
			'isl' => 'Isl',
			'isl_alt' => 'Isl Alt',
			'ita' => 'Ita',
			'ita_alt' => 'Ita Alt',
			'lav' => 'Lav',
			'lav_alt' => 'Lav Alt',
			'lit' => 'Lit',
			'lit_alt' => 'Lit Alt',
			'ndl' => 'Ndl',
			'ndl_alt' => 'Ndl Alt',
			'nno' => 'Nno',
			'nno_alt' => 'Nno Alt',
			'nob' => 'Nob',
			'nob_alt' => 'Nob Alt',
			'pol' => 'Pol',
			'pol_alt' => 'Pol Alt',
			'por' => 'Por',
			'por_alt' => 'Por Alt',
			'ron' => 'Ron',
			'ron_alt' => 'Ron Alt',
			'rus' => 'Rus',
			'rus_alt' => 'Rus Alt',
			'slk' => 'Slk',
			'slk_alt' => 'Slk Alt',
			'slv' => 'Slv',
			'slv_alt' => 'Slv Alt',
			'spa' => 'Spa',
			'spa_alt' => 'Spa Alt',
			'srp' => 'Srp',
			'srp_alt' => 'Srp Alt',
			'swe' => 'Swe',
			'swe_alt' => 'Swe Alt',
			'tur' => 'Tur',
			'tur_alt' => 'Tur Alt',
			'ukr' => 'Ukr',
			'ukr_alt' => 'Ukr Alt',
			'curID' => 'Cur',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('isoID',$this->isoID);
		$criteria->compare('iso2',$this->iso2,true);
		$criteria->compare('iso3',$this->iso3,true);
		$criteria->compare('dialcode',$this->dialcode,true);
		$criteria->compare('disabled',$this->disabled);
		$criteria->compare('mul',$this->mul,true);
		$criteria->compare('bul',$this->bul,true);
		$criteria->compare('bul_alt',$this->bul_alt,true);
		$criteria->compare('cat',$this->cat,true);
		$criteria->compare('cat_alt',$this->cat_alt,true);
		$criteria->compare('ces',$this->ces,true);
		$criteria->compare('ces_alt',$this->ces_alt,true);
		$criteria->compare('dan',$this->dan,true);
		$criteria->compare('dan_alt',$this->dan_alt,true);
		$criteria->compare('deu',$this->deu,true);
		$criteria->compare('deu_alt',$this->deu_alt,true);
		$criteria->compare('ell',$this->ell,true);
		$criteria->compare('ell_alt',$this->ell_alt,true);
		$criteria->compare('eng',$this->eng,true);
		$criteria->compare('eng_alt',$this->eng_alt,true);
		$criteria->compare('epo',$this->epo,true);
		$criteria->compare('epo_alt',$this->epo_alt,true);
		$criteria->compare('est',$this->est,true);
		$criteria->compare('est_alt',$this->est_alt,true);
		$criteria->compare('fin',$this->fin,true);
		$criteria->compare('fin_alt',$this->fin_alt,true);
		$criteria->compare('fra',$this->fra,true);
		$criteria->compare('fra_alt',$this->fra_alt,true);
		$criteria->compare('hrv',$this->hrv,true);
		$criteria->compare('hrv_alt',$this->hrv_alt,true);
		$criteria->compare('hun',$this->hun,true);
		$criteria->compare('hun_alt',$this->hun_alt,true);
		$criteria->compare('ind',$this->ind,true);
		$criteria->compare('ind_alt',$this->ind_alt,true);
		$criteria->compare('isl',$this->isl,true);
		$criteria->compare('isl_alt',$this->isl_alt,true);
		$criteria->compare('ita',$this->ita,true);
		$criteria->compare('ita_alt',$this->ita_alt,true);
		$criteria->compare('lav',$this->lav,true);
		$criteria->compare('lav_alt',$this->lav_alt,true);
		$criteria->compare('lit',$this->lit,true);
		$criteria->compare('lit_alt',$this->lit_alt,true);
		$criteria->compare('ndl',$this->ndl,true);
		$criteria->compare('ndl_alt',$this->ndl_alt,true);
		$criteria->compare('nno',$this->nno,true);
		$criteria->compare('nno_alt',$this->nno_alt,true);
		$criteria->compare('nob',$this->nob,true);
		$criteria->compare('nob_alt',$this->nob_alt,true);
		$criteria->compare('pol',$this->pol,true);
		$criteria->compare('pol_alt',$this->pol_alt,true);
		$criteria->compare('por',$this->por,true);
		$criteria->compare('por_alt',$this->por_alt,true);
		$criteria->compare('ron',$this->ron,true);
		$criteria->compare('ron_alt',$this->ron_alt,true);
		$criteria->compare('rus',$this->rus,true);
		$criteria->compare('rus_alt',$this->rus_alt,true);
		$criteria->compare('slk',$this->slk,true);
		$criteria->compare('slk_alt',$this->slk_alt,true);
		$criteria->compare('slv',$this->slv,true);
		$criteria->compare('slv_alt',$this->slv_alt,true);
		$criteria->compare('spa',$this->spa,true);
		$criteria->compare('spa_alt',$this->spa_alt,true);
		$criteria->compare('srp',$this->srp,true);
		$criteria->compare('srp_alt',$this->srp_alt,true);
		$criteria->compare('swe',$this->swe,true);
		$criteria->compare('swe_alt',$this->swe_alt,true);
		$criteria->compare('tur',$this->tur,true);
		$criteria->compare('tur_alt',$this->tur_alt,true);
		$criteria->compare('ukr',$this->ukr,true);
		$criteria->compare('ukr_alt',$this->ukr_alt,true);
		$criteria->compare('curID',$this->curID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}