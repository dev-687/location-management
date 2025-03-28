@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-right mb-5"><a href="{{route('location.index')}}" class="bg-blue-500 mb-10 t text-white rounded p-2">Stored Locations</a></div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Add State, City, Pincode</h1>

                    <div id="messageArea"></div>

                    <form id="dataForm" method="POST" action="{{ route('location.store') }}">
                        @csrf
                        <div id="dataRows">
                            <div class="dataRow mb-4">
                                <select name="state[]" class="stateSelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                <select name="city[]" class="citySelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select City</option>
                                </select>
                                <input type="text" name="pincode[]" placeholder="Pincode" class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <button type="button" class="removeRow bg-red-500 text-white rounded p-2">Remove</button>
                            </div>
                        </div>
                        <button type="button" id="addRow" class="bg-blue-500 text-white rounded p-2">Add Row</button>
                        <button type="button" id="submitForm" class="bg-green-500 text-white rounded p-2">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#addRow').click(function() {
                var newRow = $('.dataRow:first').clone();
                newRow.find('input').val('');
                newRow.find('select.citySelect').html('<option value="">Select City</option>');
                $('#dataRows').append(newRow);
            });

            $(document).on('click', '.removeRow', function() {
                if ($('.dataRow').length > 1) {
                    $(this).closest('.dataRow').remove();
                }
            });


            $(document).on('change', '.stateSelect', function() {
                var stateId = $(this).val();
                var citySelect = $(this).closest('.dataRow').find('.citySelect');

                if (stateId) {
                    $.ajax({
                        url: '/get-cities/' + stateId,
                        type: 'GET',
                        success: function(data) {
                            citySelect.html('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                citySelect.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    citySelect.html('<option value="">Select City</option>');
                }
            });

            $('#submitForm').click(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $('#dataForm').attr('action'),
                    type: 'POST',
                    data: $('#dataForm').serialize(),
                    success: function(response) {
                        $('#messageArea').html('<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Success!</strong><span class="block sm:inline">' + response.message + '</span></div>');
                        $('#dataForm')[0].reset();
                        $('#dataRows').html('<div class="dataRow mb-4"><select name="state[]" class="stateSelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"><option value="">Select State</option>@foreach($states as $state)<option value="{{ $state->id }}">{{ $state->name }}</option>@endforeach</select><select name="city[]" class="citySelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"><option value="">Select City</option></select><input type="text" name="pincode[]" placeholder="Pincode" class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white"><button type="button" class="removeRow bg-red-500 text-white rounded p-2">Remove</button></div>');
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = '';
                        $.each(errors, function(key, value) {
                            errorMessages += '<p>' + value[0] + '</p>';
                        });
                        $('#messageArea').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Error!</strong><span class="block sm:inline">' + errorMessages + '</span></div>');
                    }
                });
            });
        });
    </script>
@endsection
