@php
    // TODO blade directive?
    $submit = \Request::session()->has('_old_input');
    if ($submit) {
        $messages = $errors->get($name);
        if ($messages) {
            $inputClass = ' is-invalid';
            $feedbackClass = 'invalid-feedback';
            $feedbackHtml = implode('<br>', array_map('e', $messages));
        } else {
            $inputClass = ' is-valid';
            $feedbackClass = 'valid-feedback';
            $feedbackHtml = null;
        }
    } else {
        $inputClass = null;
        $feedbackHtml = null;
    }
@endphp
<label for="{{ $name }}">{{ __('books.' . $name) }}</label>
<input type="{{ $type ?? 'text' }}" class="form-control{{ $inputClass }}"
       name="{{ $name }}" value="{{ old($name) }}"
       placeholder="{{ $placeholder ?? '' }}" required>
@if ($feedbackHtml)
    <div class="{{ $feedbackClass }}">{!! $feedbackHtml !!}</div>
@endif
