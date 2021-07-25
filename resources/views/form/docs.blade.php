<x-forms-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.1.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.1.0/highlight.min.js"></script>
    <script>hljs.highlightAll();</script>
    <div class="ml-4">
        @foreach($only as $question)
            <?php $object = config('forms.questions.' . $question); $obj = new $object; ?>
            <x-forms::question :title="$question">
                @if($obj->field!="question")
                    <span class="font-bold">Answer format:</span>
                    <pre><code>[{{$obj->field}}: {{$obj::answer_format}}]</code></pre><br>
                @endif
                @if(!empty($obj::codes) or !empty($obj::codes_old))
                    <span class="font-bold">Codes</span>
                    <table>
                        @foreach($obj::codes as $k=>$c)
                            <tr>
                                <td class="w-24">{{$k}}</td>
                                <td>{{$c}}</td>
                            </tr>
                        @endforeach
                        @foreach($obj::codes_old as $k=>$c)
                            <tr>
                                <td class="w-24 line-through">{{$k}}</td>
                                <td class="line-through">{{$c}}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif
                <span class="text-red-600">{{$obj::comments}}</span>
            </x-forms::question>
        @endforeach
    </div>
</x-forms-guest-layout>