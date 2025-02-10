<?php


namespace App\Console;


use App\Models\ApiCredential;
use Illuminate\Console\Command;

class CredentialRepoCommand extends Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = 'credential:set {--name=} {--password=}  {--username=}  {--pin=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt and edit existing credentials. They will be decrypted after saving.';

    /**
     * The command handler.
     *
     * @return void
     */
    public function handle()
    {

        $options = $this->options();
        $validator = validator($options,[
            'name'=>'required',
            'password'=>'required',
            'username'=>'required',
        ]);

        if ($validator->fails()){
            $this->error("Failed: ".$validator->errors()->first());
            return;
        }

        $ac = ApiCredential::query()->where(['name'=>$options['name']])->first();

        if (!empty($ac)){
            $ac->update([
                'password'=>encrypt($options['password']),
                'username'=>$options['username'],
                'pin'=>$options['pin']
            ]);

            $this->info('Successfully created new credential.');
        }else{
            ApiCredential::query()->create(['name'=>$options['name'],
                    'password'=>encrypt($options['password']),
                    'username'=>$options['username'],
                    'pin'=>$options['pin']
                ]
            );

            $this->info('Successfully updated credentials.');
        }
    }
}
