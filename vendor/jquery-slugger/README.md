# jquery-slugger
A jQuery plugin to auto generate a url friendly slug ('slugger' as in `slugify` is already taken).

## Getting Started
This guide will help you install and use jQuery Slugger. It should work on jQuery v1.2.3 and above.

### Installation
via bower:
```
bower install jquery-slugger
```

via npm:
```
npm install jquery-slugger
```
or download or clone on [GitHub](https://github.com/lioneil/jquery-slugger).

### Usage
Just add it to your project and identify the elements using `data-slugger`.


1. Sample use case (basic):
```html
<form>
  <div class="form-group">
	  <label for="title">Title</label>
    <!-- auto initialize via data-slugger -->
		<input id="title" type="text" class="form-control" name="title" data-slugger>
	</div>
	<div class="form-group">
	  <label for="slug">Slug</label>
    <!-- jQuery Slugger, by default, will target all `[name=slug]` -->
		<input id="slug" type="text" class="form-control" name="slug">
	</div>
</form>
...
<!-- jQuery is, of course, a dependency -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!-- Our main man here -->
<script src="jquery-slugger/dist/jquery.slugger.min.js"></script>
```

2. Using a custom target:
```html
<form>
  <div class="form-group">
	  <label for="title">Title</label>
    <!-- auto initialize via data-slugger -->
		<input id="title" type="text" class="form-control" name="title" data-slugger=".to-slugify">
	</div>
	<div class="form-group">
	  <label for="slug">Slug</label>
		<input id="slug" type="text" class="form-control to-slugify" name="slug">
	</div>
  <div class="form-group">
	  <label for="url">URL</label>
		<input id="url" type="text" class="form-control to-slugify" name="url">
	</div>
</form>
```

3. Using a custom separator:
```html
<form>
  <div class="form-group">
	  <label for="fullname">Full Name</label>
    <!-- auto initialize via `data-slugger`, `data-slug-separator` is for defining custom space replacer -->
		<input id="fullname" type="text" class="form-control" name="fullname" data-slugger="#username" data-slug-separator="_">
	</div>
	<div class="form-group">
	  <label for="username">Username</label>
		<input id="username" type="text" class="form-control" name="username">
	</div>
</form>
```


### Options
The plugin have options you can modify. Below is the list of options with their default values:
```
$('#slugger-element').slugger({
  bindToEvent: 'keypress keyup', // The event to bind to.
  target: '[name=slug]',
  separator: '-',

  convertToLowerCase: true,
  isUrlFriendly: true,

  beforeConvert: function (self) {},
  afterConvert: function (self) {},

  debug: false,
});
```

### Deployment
Copy the /dist/\*.min.js folder to your project


### Versioning
The project uses [SemVer](http://semver.org) for versioning. For the versions available, see the tags on this repository.


### Authors
* John Lioneil Dionisio

See also the list of [contributors](#) who participated in this project.


### License
[MIT License](https://raw.githubusercontent.com/lioneil/jquery-slugger/master/LICENSE)


### Acknowledgment
* Various resources.
* to the Muses of Inspiration.
