<?php

class Favorite extends Eloquent
{
    protected $fillable = ['user_id', 'comic_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comic()
    {
        return $this->belongsTo('Comic');
    }


    /**
     * @params Iterator $comics
     * @return array
     */
    public static function favoritesHashByComics($comics)
    {
        $ids = [];
        foreach ($comics as $comic) {
            $ids[] = $comic->id;
        }
        if (empty($ids)) {
            return [];
        }
        $favorites = self::where('user_id', '=', Auth::user()->id)
            ->whereIn('comic_id', $ids)
            ->get();
        $comicIdKeyHash = [];
        foreach ($favorites as $favorite) {
            $comicIdKeyHash[$favorite->comic_id] = $favorite;
        }
        return $comicIdKeyHash;
    }

}