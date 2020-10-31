# File Input
This package is written for simple file input. It allows to store in your local directory or cloudinary platform (online media storage).

## Installation
```bash
composer require toetet/fileinput
```

Publish configuration file
```bash
php artisan vendor:publish --provider="Toetet\FileInput\FileInputServiceProvider"
```

This will add new configuration file.

You can find 
- config file at config/fileinput.php

In thease publish files, you are free to change them to anything to better match your application.

## Documentation
#### Include File Input Filter Input
In your blade file, you can get File Input filter input easily as below.
```bash
\Toetet\FileInput\Facades\Nrc::input();
```

#### Get File Input Input Data
```bash
Nrc::data($request);				// 		1/KaMaTa(N)849832

Nrc::stateRegion($request);			//		1

Nrc::citizen($request);				//		N

Nrc::township($request);			//		KaMaTa

Nrc::number($request);				//		849832
```

#### Get State Region, Township, Citizen, Number by File Input String
File Input string must be valid format.
In the format, "/", "(", ")" characters are essential.
```bash
$nrc = 	"Kachin/KAMATA(NAING)849832";		//		{state_region}/{township}({citizen}){number}

$nrc = 	"ကချင်ပြည်နယ်/ကမတ(နိုင်)၈၄၉၈၃၂";		//		{state_region}/{township}({citizen}){number}

$nrc = 	"1/KaMaTa(N)849832";			//		{state_region}/{township}({citizen}){number}

Nrc::getStateRegion($nrc);			//		1

Nrc::getCitizen($nrc);				//		N

Nrc::getTownship($nrc);				//		KaMaTa

Nrc::getNumber($nrc);				//		849832
```

## License
This package is open-sourced software licensed under the [MIT](https://choosealicense.com/licenses/mit/) license.
You are free to use it in personal and commercial projects.
