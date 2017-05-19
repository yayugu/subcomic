<?php

class History extends Eloquent
{
    protected $fillable = ['user_id', 'comic_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comic()
    {
        return $this->belongsTo('Comic');
    }
}