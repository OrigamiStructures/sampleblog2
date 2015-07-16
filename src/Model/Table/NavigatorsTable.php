<?php
namespace App\Model\Table;

use App\Model\Entity\Navigator;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Navigators Model
 */
class NavigatorsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('menus');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
		$this->addBehavior('Tree', [
            'scope' => ['type' => 'main']
        ]);
        $this->hasMany('SubNavigators', [
            'className' => 'Navigators',
			'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('ParentNavigators', [
            'className' => 'Navigators',
			'foreignKey' => 'parent_id'
        ]);    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->allowEmpty('model');
            
        $validator
            ->allowEmpty('label');
            
        $validator
            ->allowEmpty('controller');
            
        $validator
            ->allowEmpty('action');
            
        $validator
            ->allowEmpty('query');
            
        $validator
            ->allowEmpty('hash');

        return $validator;
    }
}
