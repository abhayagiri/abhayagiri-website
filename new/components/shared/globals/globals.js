/*
 * React's context feature was not intended for application state and
 * using it as such can lead to hard-to-track-down problems:
 *
 * https://reactjs.org/docs/context.html#why-not-to-use-context
 *
 * This module works around these issues by using a dependency injection
 * pattern with context as described in the following article:
 *
 * https://medium.com/@mweststrate/how-to-safely-use-react-context-b7e343eff076
 *
 * Setup
 * -----
 *
 * Add the <GlobalsApp> component to the root of the app heirarchy.
 *
 * Usage
 * -----
 *
 * Wrap your component with the withGlobals higher-order component (HOC):
 *
 *     withGlobals(MyComponent, 'color');
 *
 * MyComponent will have the property `color` and any changes to the color
 * globals will update the `color` props.
 *
 * More...
 * -------
 *
 * You can use the GlobalsProvider in the render method of
 * the component to set a global:
 *
 *     render() {
 *         <GlobalsProvider state={{ 'color': this.state.color }}>
 *             ...
 *         </GlobalsProvider>
 *
 * You can use the GlobalsListener to get a global as a prop:
 *
 *     render() {
 *         <GlobalsListener map="color">
 *             <MyComponent />
 *         </GlobalsProvider>
 *
 * MyComponent will now have the color global as a prop.
 *
 * You can also directly use globals.get, globals.set, globals.subscribe
 * and globals.unsubscribe context as needed. Just be sure to add the
 * appropriate methods to component's lifecycle methods, in particular:
 *
 * - constructor: initial get()
 * - componentDidMount(): subscriptions and initial set() from props
 * - componentWillReceiveProps(): subsequent set() from props
 * - componentWillUnmount(): remove subscriptions
 *
 * References
 * ----------
 *
 * - https://medium.com/react-ecosystem/how-to-handle-react-context-a7592dfdcbc
 * - https://github.com/ReactTraining/react-broadcast
 * - https://github.com/acdlite/recompose
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';
import hoistStatics from 'hoist-non-react-statics'
import Emitter from 'tiny-emitter';

class GlobalsStore {

    constructor() {
        this.state = {};
        this.emitter = new Emitter();
    }

    get(key) {
        return this.state[key];
    }

    set(key, value) {
        this.state[key] = value;
        this.emitter.emit(key, value);
        return value;
    }

    subscribe(key, callback) {
        return this.emitter.on(key, callback);
    }

    unsubscribe(key, callback) {
        return this.emitter.off(key, callback);
    }

}

export class GlobalsApp extends Component {

    static childContextTypes = {
        globals: PropTypes.object.isRequired
    }

    static propTypes = {
        children: PropTypes.node.isRequired
    }

    constructor(props, context) {
        super(props, context);
        this.globals = new GlobalsStore();
    }

    getChildContext() {
        return { globals: this.globals };
    }

    render() {
        return React.Children.only(this.props.children);
    }

}

export class GlobalsProvider extends Component {

    static contextTypes = {
        globals: PropTypes.object.isRequired
    }

    static propTypes = {
        children: PropTypes.node.isRequired,
        state: PropTypes.object.isRequired
    }

    componentDidMount() {
        for (const key in this.props.state) {
            this.context.globals.set(key, this.props.state[key]);
        }
    }

    componentWillReceiveProps(nextProps) {
        for (const key in nextProps.state) {
            this.context.globals.set(key, nextProps.state[key]);
        }
    }

    render() {
        return React.Children.only(this.props.children);
    }

}

export class GlobalsListener extends Component {

    static contextTypes = {
        globals: PropTypes.object.isRequired
    }

    static propTypes = {
        children: PropTypes.func.isRequired,
        map: PropTypes.oneOfType([
            PropTypes.object,
            PropTypes.array,
            PropTypes.string
        ])
    }

    constructor(props, context) {
        super(props, context);
        this.setMap(props.map);

        let state = {};
        for (const key in this.map) {
            state[key] = context.globals.get(key);
        }
        this.state = state;
        this.listeners = {};
    }

    setMap(map) {
        if (Array.isArray(map)) {
            this.map = map.reduce((map, key) => {
                map[key] = key;
                return map;
            }, {});
        } else if (typeof map === 'object') {
            this.map = map;
        } else if (map) {
            this.map = { [map]: map };
        } else {
            this.map = {};
        }
    }

    componentDidMount() {
        const { map } = this;
        for (const key in map) {
            const listener = (value) => {
                this.setState({ [map[key]]: value });
            };
            this.listeners[key] = listener;
            this.context.globals.subscribe(key, listener);
        }
    }

    componentWillUnmount() {
        for (const key in this.map) {
            this.context.globals.unsubscribe(key, this.listeners[key]);
        }
    }

    render() {
        let props = {};
        for (const key in this.map) {
            props[this.map[key]] = this.state[key];
        }
        return this.props.children(props);
    }

}

export function withGlobals(SubComponent, map) {

    const displayName = (SubComponent.displayName || SubComponent.name
        || 'Component');

    class WithGlobals extends Component {

        static displayName = `WithGlobals(${displayName})`;

        render() {
            const parentProps = this.props;
            return (
                <GlobalsListener map={map}>
                    {(props) => {
                        props = Object.assign(props, parentProps);
                        return <SubComponent {...props} />;
                    }}
                </GlobalsListener>
            );
        }
    };

    return hoistStatics(WithGlobals, SubComponent);
}
