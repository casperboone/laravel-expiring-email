<div class="w-full h-full">
    <div class="flex justify-between items-center p-4">
        <div>
            <strong>Subject:</strong> {{ $email->subject }}<br/>
            <strong>Expiry Date:</strong> {{ $email->expires_at }}
        </div>
        @if ($email->attachments->isNotEmpty())
            <div class="flex items-center">
                <strong>Attachments:</strong>
                @foreach ($email->attachments as $attachment)
                    <a href="{{ $attachment->url() }}" class="no-underline">
                        <button
                            class="border-0 rounded-full bg-gray-100 hover:bg-gray-200 py-2 px-4 flex items-center ml-2 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                            {{ $attachment->filename }}
                        </button>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>


<style>
    html {
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        line-height: 1.5;
    }

    body {
        margin: 0;
    }

    .w-full {
        width: 100%;
    }

    .h-full {
        height: 100%;
    }

    .p-4 {
        padding: 1rem;
    }

    .justify-between {
        justify-content: space-between;
    }

    .items-center {
        align-items: center;
    }

    .flex {
        display: flex;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .items-center {
        align-items: center;
    }

    .flex {
        display: flex;
    }

    .rounded-full {
        border-radius: 9999px;
    }

    .bg-gray-100 {
        --tw-bg-opacity: 1;
        background-color: rgba(243, 244, 246, var(--tw-bg-opacity));
    }

    .ml-2 {
        margin-left: 0.5rem;
    }

    .w-5 {
        width: 1.25rem;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .h-5 {
        height: 1.25rem;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .border-0 {
        border-width: 0px;
    }

    .hover\:bg-gray-200:hover {
        --tw-bg-opacity: 1;
        background-color: rgba(229, 231, 235, var(--tw-bg-opacity));
    }

    .no-underline {
        text-decoration: none;
    }
</style>
