# {{ str($resource->resource)->studly()->singular() }}: {{ str($resource->verb)->studly() }}

## Description
Route / web endpoint for the {{ str($resource->resource)->studly()->singular() }} model's reourceful routing verb {{ str($resource->verb)->studly() }}.
@if($resource->verb==='index')
Displays an index of {{ str($resource->resource)->lower()->plural() }} on screen, paginated.
@elseif($resource->verb==='show')
Displays a detailed single {{ str($resource->resource)->lower()->single() }} record on screen based on the identifier in the URL.
@elseif($resource->verb==='create')
Displays a form for creating a new {{ str($resource->resource)->lower()->single() }} record.
@elseif($resource->verb==='store')
Validates, authorizes, and processes the submission of a form for creating a new {{ str($resource->resource)->lower()->single() }} record, and redirects away.
@elseif($resource->verb==='edit')
Displays a form for editing an existing {{ str($resource->resource)->lower()->single() }} record.
@elseif($resource->verb==='update')
Validates, authorizes, and processes the submission of a form for editing an existing {{ str($resource->resource)->lower()->single() }} record, and redirects away.
@elseif($resource->verb==='destroy')
Validates, authorizes, and processes the submission of a form for deleting an existing {{ str($resource->resource)->lower()->single() }} record, and redirects away.
@endif

## Route

| Property | Value |
|----------|-------|
| Method | {{ $resource->httpMethod() }} |
| URI | {{ $resource->uri() }} |
| Name | {{ $resource->routeName() }} |
| Middleware | {{ implode(', ', $resource->middleware()) }} |

@if($resource->routeParameters()->isNotEmpty())
    ## Route Parameters

    | Parameter | Type | Model Binding | Description |
    |-----------|------|---------------|-------------|
    @foreach($resource->routeParameters() as $param)
        | {{ $param->name }} | {{ $param->type }} | {{ $param->binding ?? '-' }} | {{ $param->description ?? '-' }} |
    @endforeach
@endif

## Props

| Prop | Type | Source | Always Present | Description |
|------|------|--------|----------------|-------------|
@foreach($resource->props() as $prop)
    | {{ $prop->name }} | {{ $prop->type }} | {{ $prop->source }} | {{ $prop->always ? 'yes' : 'no' }} | {{ $prop->description ?? '-' }} |
@endforeach

@if($resource->validationRules()->isNotEmpty())
    ## Validation

    | Field | Rules | Type | Description |
    |-------|-------|------|-------------|
    @foreach($resource->validationRules() as $field)
        | {{ $field->name }} | {{ $field->rules }} | {{ $field->frontendType }} | {{ $field->description ?? '-' }} |
    @endforeach
@endif

## Authorization
{{ $resource->authorization() ?? 'None' }}

## Dependencies

| Type | Reference | Purpose |
|------|-----------|---------|
| Model | {{ $resource->primaryModel() }} | Primary resource |
| Action | {{ $resource->action()->qualifiedClassName() }} | Business logic |
@if($resource->formRequest())
    | FormRequest | {{ $resource->formRequest()->qualifiedClassName() }} | Validation |
@endif
| Policy | {{ $resource->policyMethod() ?? '-' }} | Authorization |
@foreach($resource->additionalModels() as $model)
    | Model | {{ $model->name }} | {{ $model->purpose }} |
@endforeach
TOOD: Query, command, form request class

## Files
- Controller: {{ $resource->controller->qualifiedFilename() }}
- Action: {{ $resource->action->qualifiedFilename() }}
- Component: {{ $resource->component->qualifiedFilename() }}
- Types: {{ $resource->componentTypes->qualifiedFilename() }}
- Route: {{ $resource->route->qualifiedFilename() }}
- Feature Test: {{ $resource->featureTest->qualifiedFilename() }}
- Browser Test: {{ $resource->browserTest->qualifiedFilename() }}

## Verb: {{ $resource->verb }}

| Property | Value |
|----------|-------|
| Category | {{ Verbs::isQuery($resource->verb) ? 'Query' : 'Command (Mutation)' }} |
| Paginated | {{ $resource->paginated ? 'yes' : 'no' }} |
| Modal | {{ $resource->modal ? 'yes' : 'no' }} |
| Redirects To | {{ $resource->redirectsTo ?? '-' }} |

## Related Routes
@foreach($resource->relatedRoutes() as $route)
    - [{{ $route->verb }}]({{ $route->skillFilePath() }}): {{ $route->description ?? $route->routeName() }}
@endforeach

## Notes
--

<!-- Generated: {{ now()->toDateTimeString() }} by generate:route -->
<!-- Last updated: {{ now()->toDateTimeString() }} -->
