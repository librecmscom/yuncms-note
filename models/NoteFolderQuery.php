<?php

namespace yuncms\note\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[NoteFolder]].
 *
 * @see NoteFolder
 */
class NoteFolderQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NoteFolder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NoteFolder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
