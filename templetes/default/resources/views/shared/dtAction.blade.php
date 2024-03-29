<div class="btn-group btn-group-sm" role="group">
    @isset($showUrl)
        <a class="btn btn-white btn-show" rel="{{ $id?? '' }}" href="{{ $showUrl }}">
            <i class="material-icons">remove_red_eye</i>
        </a>
    @endisset
    @isset($editUrl)
        <a class="btn btn-white btn-edit" rel="{{ $id?? '' }}" href="{{ $editUrl }}">
            <i class="material-icons">edit</i>
        </a>
    @endisset
    @isset($deleteUrl)
         <form action="{{ $deleteUrl }}" method="POST">
            @method('DELETE')
            @csrf
            <button class="btn btn-white btn-delete" rel="{{ $id ?? '' }}">
                <i class="material-icons">delete</i>
            </button>
         </form>
    @endisset
    @isset($downloadUrl)
        <a href="{{ $downloadUrl }}" rel="{{ $id ?? '' }}" class="btn btn-white">
            <i class="fas fa-download"></i>
        </a>
    @endisset
</div>

