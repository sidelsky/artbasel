/**
 * Block dependencies
 */

'use strict';
/**
 * Internal block libraries
 */


import './block_playlist.scss';
import * as configSampleData from '../../configs/sampledata-playlists';
import * as helpers from './js_common/_helpers';
import SamplesHolder from './js_dzsap/SamplesHolder';

let __ = (arg) => {
  return arg;
};


if (wp.i18n) {
  __ = wp.i18n.__;
}

const {registerBlockType} = wp.blocks;

const {
  SelectControl,
  ServerSideRender,
} = wp.components;

/**
 * Register block
 */





const key_block = 'dzsap/gutenberg-playlist';
export default registerBlockType(key_block, {
  // Block Title
  title: 'ZoomSounds ' + __('Playlist'),
  // Block Description
  description: __('Powerful audio player playlist'),
  // Block Category
  category: 'common',
  // Block Icon
  icon: 'format-audio',
  // Block Keywords
  keywords: [
    __('Audio gallery'),
    __('Playlist'),
    __('Zoomsounds'),
    __('Sound'),
  ],
  attributes: window.dzsap_gutenberg_playlist_options_for_js_init,
  // Defining the edit interface
  edit: props => {
    const {
      attributes
    } = props;

    function dzsap_setShortcodeAttribute(args) {

      props.setAttributes(args);
    }

    const import_sample = (arg) => {


      if (arg && arg.getAttribute('data-the-name')) {


        var theName = arg.getAttribute('data-the-name');

        helpers.postAjax(dzsap_settings.siteurl + '?dzsap_action=dzsap_import_playlist&name='+theName, 'name=' + theName, (arg) => {

          var fout = helpers.decode_json(arg);
          sliders.push({
            'value': fout.slider_slug,
            'label': fout.slider_name,
          });
          dzsap_setShortcodeAttribute({dzsap_select_id: fout.slider_slug});
        });
      }
    };

    const examples_con_opened = props.attributes.examples_con_opened;
    const arr_examples = configSampleData.arr_examples;
    const self = this;
    const sliders = window.dzsap_settings.sliders;

    let selectedTermId = '';


    if (props.attributes.dzsap_select_id) {
      sliders.forEach((arg) => {

        if (props.attributes.dzsap_select_id === arg.value) {
          selectedTermId = arg.term_id;
        }
      })
    }
    let editUrl = helpers.add_query_arg(dzsap_settings.admin_url + 'term.php', 'taxonomy', 'dzsap_sliders');
    editUrl = helpers.add_query_arg(editUrl, 'post_type', 'dzsap_items');
    editUrl = helpers.add_query_arg(editUrl, 'tag_ID', selectedTermId);
    return [
      <div className={props.className}>
        <div className={(props.attributes.expanded ? 'gt-playlist-expanded' : ' ')}>
          <div className="zoomsounds-containers">
            <h4>{__('Zoomsounds Playlist')}</h4>
            <div className="react-setting-container">
              <div className="react-setting-container--label">{__('Playlist')}</div>
              <div className="react-setting-container--control">

                <SelectControl

                  value={props.attributes.dzsap_select_id}
                  options={sliders}
                  onChange={(val) => {
                    props.setAttributes({dzsap_select_id: val})
                  }}
                />
              </div>
            </div>
            {props.attributes.dzsap_select_id ? (
              <div className={"sidenote"}>{__('Edit playlist from ')}
                <a className='ultibox-item-delegated'
                   href={editUrl}>{__('here')}</a></div>
            ) : ''}


            <div className="dzs--gutenberg-preview-block">
              <ServerSideRender
                block={key_block}
                attributes={props.attributes}
              />
              <div className={"button-secondary2 preview-block--expander"} onClick={() => {
                const _c = document.querySelector('.dzs--gutenberg-preview-block');
                if (_c.classList.contains('expanded')) {

                  _c.classList.remove('expanded');
                  _c.querySelector('.expander-icon').innerHTML = '&#x2207;';
                } else {

                  _c.classList.add('expanded');
                  _c.querySelector('.expander-icon').innerHTML = '&#x2206;';
                }
              }}><span className="expander-label">{__("Preview Expand")}</span> <span
                className="expander-icon">&#x2207;</span></div>
            </div>


            <SamplesHolder
              examples_con_opened={examples_con_opened}
              main_props={props}
              __={__}
              dzsap_settings={dzsap_settings}
              import_sample={import_sample}
              arr_examples={arr_examples}
            />


          </div>
        </div>
      </div>
    ];
  },

  save() {
    // -- Rendering in PHP
    return null;
  },
});
