@php echo '<?php'.PHP_EOL @endphp

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{{ $pivotMigration->tableName() }}', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\{{ $resource->resource }}::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\{{ $pivotMigration->relationshipClass() }}::class)->constrained()->cascadeOnDelete();
@if($pivotMigration->relationship->pivot && !empty($pivotMigration->relationship->pivot['columns']))
@foreach($pivotMigration->relationship->pivot['columns'] as $column)
            $table->string('{{ $column }}')->nullable();
@endforeach
@endif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{{ $pivotMigration->tableName() }}');
    }
};
