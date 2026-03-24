@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="max-w-md mx-auto">
                <h1 class="text-2xl font-semibold text-gray-900 mb-6">Track Your Ticket</h1>

                <form action="{{ route('tickets.track') }}" method="POST" class="mb-8">
                    @csrf
                    <div>
                        <label for="ticket_number" class="block text-sm font-medium text-gray-700">Ticket Number (e.g.
                            TIC-1001)</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="text" name="ticket_number" id="ticket_number"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-l-md sm:text-sm border-gray-300 p-2 border"
                                placeholder="TIC-1001" value="{{ old('ticket_number') }}" required>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>
                        </div>
                    </div>
                    @error('ticket_number')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </form>

                @if(isset($ticket))
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Status for {{ $ticket->ticket_number }}
                                </h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                                                                {{ $ticket->status === 'Open' ? 'bg-red-100 text-red-800' :
                    ($ticket->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </dd>
                                    </div>
                                    <div
                                        class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-100">
                                        <dt class="text-sm font-medium text-gray-500">User Problem</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $ticket->problem_description }}
                                        </dd>
                                    </div>
                                    <div
                                        class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-100">
                                        <dt class="text-sm font-medium text-gray-500">Admin Reply</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $ticket->admin_reply ?? 'No reply yet. Please check back later.' }}
                                        </dd>
                                    </div>
                                    <div
                                        class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-100">
                                        <dt class="text-sm font-medium text-gray-500">Submitted On</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            {{ $ticket->created_at->format('M d, Y h:i A') }}
                                        </dd>
                                    </div>
                                    @if($ticket->status === 'Closed' && $ticket->closed_at)
                                        <div
                                            class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-t border-gray-100">
                                            <dt class="text-sm font-medium text-gray-500">Closed On</dt>
                                            <dd class="mt-1 text-sm text-green-600 font-bold sm:mt-0 sm:col-span-2">
                                                {{ $ticket->closed_at->format('M d, Y h:i A') }}
                                                <div class="text-xs text-gray-500 font-normal italic mt-1">
                                                    Duration:
                                                    {{ $ticket->created_at->diffForHumans($ticket->closed_at, ['parts' => 2, 'join' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}
                                                </div>
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </div>
@endsection