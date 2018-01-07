import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { mount } from 'enzyme';

import { GlobalsApp, GlobalsProvider, GlobalsListener,
         withGlobals } from './globals';

describe('withGlobals', () => {

    class A extends Component {
        static contextTypes = {
            globals: PropTypes.object.isRequired
        }
        componentDidMount() {
            this.context.globals.set('color', 'red');
        }
        makeBlue() {
            this.context.globals.set('color', 'blue');
        }
        render() {
            return (
                <div>
                    <div>{this.props.children}</div>
                    <button onClick={this.makeBlue.bind(this)} />
                </div>
            );
        }
    }
    class B extends Component {
        render() {
            return <p>{this.props.color}</p>;
        }
    }
    const GB = withGlobals(B, 'color');

    class App extends Component {
        render() {
            return (
                <GlobalsApp>
                    <A>
                        <GB />
                    </A>
                </GlobalsApp>
            );
        }
    }

    const wrapper = mount(<App />);

    it('sets the color', () => {
        expect(wrapper.find('p').text()).toBe('red');
        wrapper.find('button').simulate('click');
        expect(wrapper.find('p').text()).toBe('blue');
    });

});
