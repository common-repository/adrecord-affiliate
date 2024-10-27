#Build tooling

##Prerequisites
- Node.js
- Yarn

##Building the project

**This project builds its javascript and css with Create React App.**

The php code is locationed under php/ and the javascript under src/.

To build the project, first install npm dependencies via yarn:
Installing the npm dependencies may take a while the first time.

```
yarn install
```

Then run:
```
yarn build
```


##Development
For development the project uses react-wp-scripts to allow for cross origin scripts/live reload.
react-wp-scripts serves the files on localhost:3000.
```
yarn start
```