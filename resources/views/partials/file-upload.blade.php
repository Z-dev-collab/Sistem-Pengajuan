@php
    $multiple = $multiple ?? false;
    $name = $multiple ? "documents[{$field}][]" : "documents[{$field}]";
    $inputId = 'file-'.str_replace('_', '-', $field);
    $hasError = $errors->has("documents.{$field}") || $errors->has("documents.{$field}.*");
    $oldFiles = old("documents.{$field}");
@endphp

<div class="file-upload {{ $hasError ? 'has-error' : '' }}" data-upload>
    <div class="file-upload-head">
        <div>
            <label for="{{ $inputId }}">{{ $label }} @if($required ?? true)<span class="required-star">*</span>@endif</label>
            <p>{{ $hint }}</p>
        </div>
        @if(!($required ?? true))
            <span class="tag-optional">Opsional</span>
        @else
            <span class="tag-required">Wajib</span>
        @endif
    </div>
    <label class="upload-box" for="{{ $inputId }}">
        <span class="upload-box-icon"><x-icon name="upload" :size="22" /></span>
        <span class="upload-box-copy">
            <strong data-upload-title>{{ $multiple ? 'Pilih satu atau beberapa berkas' : 'Pilih berkas atau seret ke sini' }}</strong>
            <small data-upload-name>{{ $multiple ? 'Maksimal 3 berkas' : 'Belum ada berkas dipilih' }}</small>
        </span>
        <span class="upload-box-action">Pilih File</span>
        <input
            type="file"
            id="{{ $inputId }}"
            name="{{ $name }}"
            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
            {{ ($required ?? true) ? 'required' : '' }}
            @if($multiple) multiple @endif
            data-file-input
        >
    </label>
    <div class="upload-selected" data-upload-selected hidden></div>
    <p class="field-hint">Format JPG, PNG, atau PDF · Maksimal 2 MB{{ $multiple ? ' per berkas' : '' }}</p>
    @if($hasError)
        <p class="field-error">{{ $errors->first("documents.{$field}") ?: $errors->first("documents.{$field}.*") }}</p>
    @endif
</div>
