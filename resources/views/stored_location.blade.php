@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Edit State, City, Pincode</h1>

                    <div id="messageArea"></div>

                    <form id="editForm" method="POST" action="{{ route('location.update') }}">
                        @csrf
                        @method('PUT')
                        <div id="editRows">
                            @foreach($locations as $location)
                                <div class="editRow mb-4" data-id="{{ $location->id }}">
                                    <input type="hidden" name="location_id[]" class="location_id" value="{{ $location->id }}">
                                    <select name="state[]" class="stateSelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ $location->state_id == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="city[]" class="citySelect border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="">Select City</option>
                                        @foreach($cities->where('state_id', $location->state_id) as $city)
                                            <option value="{{ $city->id }}" {{ $location->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="pincode[]" value="{{ $location->pincode }}" placeholder="Pincode" class="border rounded p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <button type="button" class="removeRow bg-red-500 text-white rounded p-2">Remove</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="addRow" class="bg-blue-500 text-white rounded p-2">Add Row</button>
                        <button type="button" id="submitEditForm" class="bg-green-500 text-white rounded p-2">Update</button>
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
                var newRow = $('.editRow:first').clone();
                newRow.find('input').val('');
                newRow.find('select.stateSelect').prop('selectedIndex', '');
                newRow.find('select.citySelect').html('<option value="">Select City</option>');
                $('#editRows').append(newRow);
            });


            $(document).on('click', '.removeRow', function() {
                if ($('.editRow').length > 1) {
                    $(this).closest('.editRow').remove();
                }
            });


            $(document).on('change', '.stateSelect', function() {
                var stateId = $(this).val();
                var citySelect = $(this).closest('.editRow').find('.citySelect');
                var location_id = $(this).closest('.editRow').find('.location_id');

                if (stateId) {
                    $.ajax({
                        url: '/get-cities/' + stateId+'/' + location_id.val(),
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


            $('#submitEditForm').click(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $('#editForm').attr('action'),
                    type: 'PUT',
                    data: $('#editForm').serialize(),
                    success: function(response) {
                        $('#messageArea').html('<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Success!</strong><span class="block sm:inline">' + response.message + '</span></div>');
                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var error = xhr.responseJSON.error;

                        if(error){
                            $('#messageArea').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Error!</strong><span class="block sm:inline">' +error+ '</span></div>');
                        }
                        else{
                            var errorMessages = '';
                        $.each(errors, function(key, value) {
                            errorMessages += '<p>' + value[0] + '</p>';
                        });
                        $('#messageArea').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Error!</strong><span class="block sm:inline">' + errorMessages + '</span></div>');
                        }


                    }
                });
            });
        });
    </script>
@endsection
