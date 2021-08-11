import React from 'react';

export default class SamplesHolder extends React.Component {
  constructor(props)  {
    super(props);
    console.log(props);

    this.props = props;
  }

  render(){
    return (
      <div className={this.props.examples_con_opened ? "gt-zoomsounds-examples-con opened" : "gt-zoomsounds-examples-con " }>
        <h6 onClick={() => {


          this.props.main_props.setAttributes( { examples_con_opened: !this.props.main_props.attributes.examples_con_opened } );
        }}>{ this.props.__( 'One click Examples' ) } <span className={"the-icon"}> &#x025BF;</span></h6>
        <div className={"sidenote"}>{ this.props.__( 'Import examples with one click' ) }</div>
        <div className={"dzs-player-examples-con"}>
          {this.props.arr_examples.map((el, ind) => {
            return (
              <div className={"dzs-player-example"} onClick={(e) => {  this.props.import_sample(e.currentTarget) }} key={ind} data-the-name={el.name}>
                <img className={"the-img"} src={this.props.dzsap_settings.thepath + el.img}/>
                <h6 className={"the-label"}>{el.label}</h6>
              </div>
            )
          })}
        </div>
      </div>
    )
  }
}