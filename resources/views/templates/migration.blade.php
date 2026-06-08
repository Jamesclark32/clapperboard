@php echo '<?php'.PHP_EOL @endphp

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{{ $resource->migration->tableName() }}', static function (Blueprint $table) {
            $table->id();
@foreach($resource->relationships->where('type', 'belongsTo') as $relationship)
            $table->foreignIdFor(\App\Models\{{ $relationship->model }}::class)->constrained()->cascadeOnDelete();
@endforeach
@foreach($resource->attributes as $attribute)
            $table->{{ $attribute->columnType }}('{{ $attribute->name }}'{!! $attribute->length !== null ? ', ' . $attribute->length : '' !!}){!! $attribute->nullable === true ? '->nullable()' : '' !!}{!! $attribute->default !== null ? '->default(' . $attribute->defaultValue() . ')' : '' !!}{!! $attribute->comment !== null ? "->comment('" . $attribute->comment . "')" : '' !!};
@endforeach
@if($resource->softDeletes)
            $table->softDeletes();
@endif
            $table->timestamps();

@foreach($resource->attributes as $attribute)
@if($attribute->unique===true)
            $table->unique('{{ $attribute->name }}');
@endif
@endforeach
@foreach($resource->attributes as $attribute)
@if($attribute->indexed===true)
            $table->index('{{ $attribute->name }}');
@endif
@if($attribute->compoundIndexWith!==null)
            $table->index(['{{ $attribute->name }}', @foreach($attribute->compoundIndexWith as $compoundIndex)'{{ $compoundIndex }}'@endforeach]);
@endif
@endforeach
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{{ $resource->migration->tableName() }}');
    }
};
