import React, { Component } from 'react';
import { mount } from 'enzyme';

import { GlobalsApp, GlobalsProvider, GlobalsListener,
	     withGlobals } from './globals';

describe('withGlobals', () => {

	class A extends Component {
		componentDidMount() {
			this.props.setGlobal('color', 'red');
		}
		makeBlue() {
			this.props.setGlobal('color', 'blue');
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
	const GA = withGlobals(A);

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
					<GA>
						<GB />
					</GA>
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
