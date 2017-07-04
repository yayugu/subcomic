<?php

class Tag extends Eloquent
{
    protected $primaryKey = 'name_sha1';
    public $incrementing = false;

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function comics()
    {
        return $this->belongsToMany('Comic', 'tag_maps', 'tag_name_sha1', 'comic_id');
    }
}
