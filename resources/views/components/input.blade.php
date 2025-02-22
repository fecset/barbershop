@props(['attributeName', 'attributeType' => 'text', 'attributeTitle' => ''])
@if(!isset($textarea))
<div class="form-group">
    <label for="input{{$attributeName}}" class="form-label">{{$attributeTitle}}</label>
    <input
        type="{{$attributeType}}"
        name="{{$attributeName}}"
        class="form-control @error($attributeName) is-invalid @enderror"
        id="input{{$attributeName}}"
        value="{{ old($attributeName, '') }}"
        aria-describedby="validation{{$attributeName}}">
    @error($attributeName)
    <div id="validation{{$attributeName}}" class="invalid-feedback">
        {{  $message }}
    </div>
    @enderror
</div>
@endif

@isset($textarea)
    <div class="form-group">
        <label for="textarea{{$attributeName}}" class="form-label">{{$attributeTitle}}</label>
        <textarea class="form-control" @error($attributeName) is-invalid="" @enderror"
            name="{{$attributeName}}"
            id="textarea{{$attributeName}}"
            aria-describedby="validation{{$attributeName}}">{{ old($attributeName, '') }}</textarea>
        @error($attributeName)
        <div id="validation{{$attributeName}}" class="invalid-feedback">
            {{  $message }}
        </div>
        @enderror
    </div>
@endisset
