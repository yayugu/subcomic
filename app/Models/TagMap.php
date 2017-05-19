<?php

class TagMap extends Eloquent
{
    protected $fillable = ['comic_id', 'tag_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comic()
    {
        return $this->belongsTo('Comic');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo('Tag');
    }
}