# 🌍 Google Maps 'data' Parameter Parser and Builder

## ℹ️ Overview
This PHP library provides a convenient way to parse and build the 'data' parameter in Google Maps URLs, specifically the Protocol Buffer implementation. It helps extract meaningful information from the encoded 'data' parameter and allows for the construction of new 'data' parameters for Google Maps URLs.

## 🔗 Background

Google Maps directions URLs often contain a 'data' parameter that encodes various details, such as coordinates, waypoints, and travel modes. This library focuses on the extraction and construction of this 'data' parameter, making it easier for developers to work with Google Maps URLs programmatically.

[https://www.google.co.uk/maps/dir/Bonn,+Germany/Berlin,+Germany/@51.6456171,7.9144552,7z/data=!3m1!4b1!4m13!4m12!1m5!1m1!1s0x47bee19f7ccbda49:0x86dbf8c6685c9617!2m2!1d7.0982068!2d50.73743!1m5!1m1!1s0x47a84e373f035901:0x42120465b5e3b70!2m2!1d13.404954!2d52.5200066?hl=en](https://www.google.co.uk/maps/dir/Bonn,+Germany/Berlin,+Germany/@51.6456171,7.9144552,7z/data=!3m1!4b1!4m13!4m12!1m5!1m1!1s0x47bee19f7ccbda49:0x86dbf8c6685c9617!2m2!1d7.0982068!2d50.73743!1m5!1m1!1s0x47a84e373f035901:0x42120465b5e3b70!2m2!1d13.404954!2d52.5200066?hl=en)

## ✨ How it Works
The 'data' parameter consists of elements separated by ! marks. Each element includes an integer id, a single character alphabetic type, and a string value. The interpretation of each element depends on its properties and position in the tree structure.

## 🛠️ Functionality
The parser extracts the 'data' parameter and organizes it into a tree of nodes, each representing a specific element in the Protocol Buffer. This structure allows developers to easily navigate and access the encoded information.

## 📖 Requirements
PHP 7.0 or higher

## 📦 Installing

```shell
$ composer require richarddobron/google-maps-data-parameter-parser
```

## ⚡️ Usage

### Parser
```php
$data = \dobron\GoogleMapsQueryArgs::decode('!3m1!4b1!4m13!4m12!1m5!1m1!1s0x47bee19f7ccbda49:0x86dbf8c6685c9617!2m2!1d7.0982068!2d50.73743!1m5!1m1!1s0x47a84e373f035901:0x42120465b5e3b70!2m2!1d13.404954!2d52.5200066');
```

### Example Output
```json
{
    "3": {
        "4": "b1"
    },
    "4": {
        "4": {
            "1": [
                {
                    "1": {
                        "1": "s0x47bee19f7ccbda49:0x86dbf8c6685c9617"
                    },
                    "2": {
                        "1": "d7.0982068",
                        "2": "d50.73743"
                    }
                },
                {
                    "1": {
                        "1": "s0x47a84e373f035901:0x42120465b5e3b70"
                    },
                    "2": {
                        "1": "d13.404954",
                        "2": "d52.5200066"
                    }
                }
            ]
        }
    }
}
```

### Builder
```php
$protocolBuffer = \dobron\GoogleMapsQueryArgs::encode($data);
```

## 🧪 Testing

```shell
$ composer tests
```

## 🤝 Contributing

We welcome contributions! If you'd like to help improve this project, feel free to open an issue or submit a pull request.

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

[blog]: https://www.facebook.com/notes/facebook-engineering/bigpipe-pipelining-web-pages-for-high-performance/389414033919
