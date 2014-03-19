<?php

class Tag extends Eloquent
{
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function comics()
    {
        return $this->hasManyThrough('Comic', 'TagMap');
    }
}