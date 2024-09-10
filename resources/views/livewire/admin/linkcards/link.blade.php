<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Settings;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $setting = '';
    public string $name = '';
    public string $valueData = '';
    public string $status = '';
    public string $type = 'text';
    public bool $isEdit = false;
    public int $id = 0;

    #[Rule('file|max:5560')]
    public $photo;

    public function with(): array
    {
        $user_id = Auth::user()->id;

        return [
            'settings' => Settings::where('name','LIKE','%'.$this->setting.'%')->paginate(10),
        ];
    }

    public function createSetting(): void
    {
        $user = Auth::user();
        $settings = new Settings();

        $validated = $this->validate([
            'name' => ['required', 'string','unique:settings', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);
        
        
        $settings->name = $this->name;

        if ($this->type == "text") {
            $settings->value = $this->valueData;
        } else if ($this->type == "image") {
            $settings->value = str_replace('public', '', $this->photo->storePublicly('public/images'));
        }

        $settings->type = $this->type;
        $settings->status = $this->status;
        $settings->save();

        $this->dispatch('setting-created', name: $user->name);
    }

    public function edit($id): void
    {
        if ($this->id == $id) {
            $this->isEdit = $this->isEdit ? false : true;
        } else {
            $this->isEdit = true;
        }

        if ($this->isEdit == true) {
            $settings = Settings::find($id);
            $this->id = $id;
            $this->name = $settings->name;
            $this->valueData = $settings->value;
            $this->type = $settings->type;
            $this->status = $settings->status;
        } else {
            $this->defaultData();
        }
    }

    public function updateSetting(): void
    {
        $user = Auth::user();
        $settings = Settings::find($this->id);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'status' => ['string', 'max:255'],
        ]);
        
        
        $settings->name = $this->name;

        if ($this->type == "text") {
            $settings->value = $this->valueData;
        } else if ($this->type == "image") {
            $settings->value = str_replace('public', '', $this->photo->storePublicly('public/images'));
        }

        $settings->type = $this->type;
        $settings->status = $this->status;
        $settings->save();

        $this->dispatch('setting-created', name: $user->name);
    }

    public function defaultData()
    {
        $this->id = 0;
        $this->name = "";
        $this->valueData = "";
        $this->type = "text";
        $this->status = "";
        $this->isEdit = false;
        $this->photo = null;
    }

}; ?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">



<button id="scanButton">Scan</button>
<button id="writeButton">Write</button>
<button id="makeReadOnlyButton">Make Read-Only</button>
<script>
  var ChromeSamples = {
    log: function() {
      var line = Array.prototype.slice.call(arguments).map(function(argument) {
        return typeof argument === 'string' ? argument : JSON.stringify(argument);
      }).join(' ');

      document.querySelector('#log').textContent += line + '\n';
    },

    clearLog: function() {
      document.querySelector('#log').textContent = '';
    },

    setStatus: function(status) {
      document.querySelector('#status').textContent = status;
    },

    setContent: function(newContent) {
      var content = document.querySelector('#content');
      while(content.hasChildNodes()) {
        content.removeChild(content.lastChild);
      }
      content.appendChild(newContent);
    }
  };
</script>

<h3>Live Output</h3>
<div id="output" class="output">
  <div id="content"></div>
  <div id="status"></div>
  <pre id="log"></pre>
</div>

<script>
scanButton.addEventListener("click", async () => {
  log("User clicked scan button");

  try {
    const ndef = new NDEFReader();
    await ndef.scan();
    log("> Scan started");

    ndef.addEventListener("readingerror", () => {
      log("Argh! Cannot read data from the NFC tag. Try another one?");
    });

    ndef.addEventListener("reading", ({ message, serialNumber }) => {
      log(`> Serial Number: ${serialNumber}`);
      log(`> Records: (${message.records.length})`);
    });
  } catch (error) {
    log("Argh! " + error);
  }
});

writeButton.addEventListener("click", async () => {
  log("User clicked write button");

  try {
    const ndef = new NDEFReader();
    await ndef.write("Hello world!");
    log("> Message written");
  } catch (error) {
    log("Argh! " + error);
  }
});

makeReadOnlyButton.addEventListener("click", async () => {
  log("User clicked make read-only button");

  try {
    const ndef = new NDEFReader();
    await ndef.makeReadOnly();
    log("> NFC tag has been made permanently read-only");
  } catch (error) {
    log("Argh! " + error);
  }
});


log = ChromeSamples.log;

if (!("NDEFReader" in window))
  ChromeSamples.setStatus("Web NFC is not available. Use Chrome on Android.");
</script>

            </div>
        </div>
    </div>
</div>