@extends('layouts.app')

@section('content')
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Submit a Ticket</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Please provide detailed information about the issue you are facing.
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">

                        <!-- Username -->
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                <div class="mt-1">
                                    <input type="text" name="username" id="username"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                        placeholder="John Doe" value="{{ old('username') }}" required>
                                </div>
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email (for
                                    notifications)</label>
                                <div class="mt-1">
                                    <input type="email" name="email" id="email"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                        placeholder="john@example.com" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                            <select id="department" name="department"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('department') == $dept ? 'selected' : '' }}>{{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fault Type -->
                        <div>
                            <label for="fault" class="block text-sm font-medium text-gray-700">Fault</label>
                            <select id="fault" name="fault"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">Select Fault Type</option>
                                <option value="IT Issue" {{ old('fault') == 'IT Issue' ? 'selected' : '' }}>IT Issue</option>
                                <option value="ERP Issue" {{ old('fault') == 'ERP Issue' ? 'selected' : '' }}>ERP Issue
                                </option>
                            </select>
                            @error('fault')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Device Name -->
                        <div>
                            <label for="device_name" class="block text-sm font-medium text-gray-700">Device Name</label>
                            <div class="mt-1">
                                <input type="text" name="device_name" id="device_name"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    placeholder="e.g. Laptop-Dell-001" value="{{ old('device_name') }}">
                            </div>
                            @error('device_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Problem Description -->
                        <div>
                            <label for="problem_description" class="block text-sm font-medium text-gray-700">
                                Problem Description
                            </label>
                            <div class="mt-1">
                                <textarea id="problem_description" name="problem_description" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md p-2"
                                    placeholder="Briefly describe the issue...">{{ old('problem_description') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Include any error messages or steps to reproduce the issue.
                            </p>
                            @error('problem_description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection