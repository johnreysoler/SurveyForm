<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Option;
use App\Models\Prerequisite;
use App\Models\Image;
use App\Models\OptionType;
use Illuminate\Database\Eloquent\SoftDeletes;
class FormSection extends Model
{
    use HasFactory,SoftDeletes;
    public function optionTypes(){
        return $this->belongsTo(OptionType::class, 'option_type_id', 'id');
    }

    public function options(){
        return $this->hasMany(Option::class, 'section_id', 'id');
    }

    public function forms(){
        return $this->belongsTo(Form::class, 'form_id', 'id');
    }

    public function images(){
        return $this->hasMany(Image::class, 'section_id', 'id');
    }

    public function prerequisites(){
        return $this->hasMany(Prerequisite::class, 'prerequisite_section_id', 'id');
    }

    public function section_prerequisites(){
        return $this->hasMany(Prerequisite::class, 'section_id', 'id');
    }
}
