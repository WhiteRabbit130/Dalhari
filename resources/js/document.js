/*
* Document JS
* - https://datatables.net/
* - https://editorjs.io/
* - https://momentjs.com/
* */

import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Paragraph from "@editorjs/paragraph";
// import Paragraph from "editorjs-paragraph-with-alignment";
import List from '@editorjs/list';
import NestedList from '@editorjs/nested-list';
import Checklist from '@editorjs/checklist';
import NestedChecklist from '@calumk/editorjs-nested-checklist';
import Image from "@editorjs/image";
import SimpleImage from "@editorjs/simple-image";
import LinkTool from '@editorjs/link';
import RawTool from '@editorjs/raw';
import Table from "@editorjs/table";
import Underline from '@editorjs/underline';
import FontSizeTool from 'editorjs-inline-font-size-tool';
import FontFamilyTool from 'editorjs-inline-font-family-tool';
import TextAlign from "@canburaks/text-align-editorjs";
import AlignmentTuneTool from "editorjs-text-alignment-blocktune";
import Quote from '@editorjs/quote';
import Warning from '@editorjs/warning';
import Marker from '@editorjs/marker';
import CodeTool from '@editorjs/code';
import InlineCode from '@editorjs/inline-code';
import Delimiter from '@editorjs/delimiter';
import Embed from '@editorjs/embed';
import DragDrop from 'editorjs-drag-drop';
import Undo from 'editorjs-undo';
import Swal from 'sweetalert2';
import moment from 'moment';


// import edjsHTML from "editorjs-html";
// import parse from "html-react-parser";

/**
 *  Export
*/
const titleInput = document.getElementById('doc-title');
const $docsTable = $('#docs-data-table');

let activeDoc = null;

/*
* TODO - add all the tools to editor.js
*
* */

// todo - add config options

/*
* Setup Editor JS
*   https://editorjs.io/
* - Quote
* - Warning
* - Marker
* - Code
* - Delimiter
* - InlineCode
* - LinkTool
* - ImageTool
* - Embed
* - Table
* */

// Initialize Editor.js
let editor = new EditorJS({
  holder: 'editor-js',
  tools: {
    header: {
      class: Header,
      // tunes: ["anyTuneName"],
      inlineToolbar: true,
      shortcut: 'CMD+SHIFT+H',
      config: {
        placeholder: 'Enter a header',
        defaultLevel: 3
      }
    },
    paragraph: {
      class: Paragraph,
      inlineToolbar: true
    },
    list: {
      class: NestedList,
      inlineToolbar: true,
    },
    image: {
      class: Image,
      config: {
        endpoints: {
          byFile: 'http://localhost:8000/uploadFile', // Your backend file uploader endpoint
          byUrl: 'http://localhost:8000/fetchUrl', // Your endpoint that provides uploading by Url
        },
        additionalRequestHeaders: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }
    },
    checklist: {
      class: Checklist,
      inlineToolbar: true,
    },
    table: Table,
    quote: Quote,
    warning: {
      class: Warning,
      inlineToolbar: true,
      shortcut: 'CMD+SHIFT+W',
      config: {
        titlePlaceholder: 'Title',
        messagePlaceholder: 'Message',
      },
    },
    embed: {
      class: Embed,
      config: {
        services: {
          youtube: true,
          coub: true
        }
      }
    },
    code: CodeTool,
    linkTool: {
      class: LinkTool,
      config: {
        // todo - figure out how to get this working
        endpoint: 'http://localhost:8000/fetchUrl', // Your backend endpoint for url data fetching
      }
    },
    raw: RawTool,
    
    underline: Underline,
    marker: {
      class: Marker,
      shortcut: 'CMD+SHIFT+M',
    },
    inlineCode: {
      class: InlineCode,
      shortcut: 'CMD+SHIFT+M',
    },
    fontFamily: FontFamilyTool,
    fontSize: FontSizeTool,
    textAlign: TextAlign,
    delimiter: Delimiter,
    anyTuneName: {
      class: AlignmentTuneTool,
      config: {
        default: "center",
        blocks: {
          header: "center",
        }
      }
    },
  },

  /**
   * onReady callback
   */
  onReady: () => {
    new Undo({ editor });
    new DragDrop(editor);
    console.log('Editor.js is ready to work!');
  },

  /**
   * onChange callback
   */
  onChange: (api, event) => {
    console.log('Now I know that Editor\'s content changed!', event)
  }
});

// Initialize dataTables
getAllDocs().then(res => {
  const { data } = res.data;
  initDocsTable(data);
}).catch(e => {
  console.log('error: ', e);
  Swal.fire({
    title: 'Error!',
    text: 'Error fetching documents',
    icon: 'error',
  });
});

/*
* Event listeners
* */

// Handle save button click
document.getElementById('save')
  .addEventListener('click', handleSaveClick);

// Handle clear button click
document.getElementById('clear')
  .addEventListener('click', clearEditor);

// Handle export button click
document.getElementById('export')
  .addEventListener('click', handleExportClick);

// Handle preview button click
// document.getElementById('preview')
//   .addEventListener('click', handlePreviewClick);

function clearEditor() {
  activeDoc = null; // set activeDoc to null
  titleInput.value = ''; // clear title input
  editor.clear(); // clear editor
}

function handleSaveClick() {
  // get title
  const title = titleInput.value.trim();
  if (!title) {
    Swal.fire({
      title: 'Title?',
      text: 'Please enter a title',
      icon: 'warning',
    })
  } else {
    editor.save().then((outputData) => {
      // check for content
      if (outputData.blocks.length === 0) {
        Swal.fire({
          title: 'Content?',
          text: 'Please enter some content',
          icon: 'warning',
        })
        return;
      }

      // add title to outputData
      outputData.title = title;

      let savePromise;
      if (activeDoc) {
        // add id to outputData
        outputData.id = activeDoc.id;
        // Updating an existing document
        savePromise = axios.patch(`/doc/${activeDoc.id}`, outputData);
      } else {
        // Creating a new document
        savePromise = axios.post('/save/doc', outputData);
        // Clear the editor
        clearEditor();
      }

      savePromise.then(function (response) {
        const { data } = response.data;
        // Check if the response is 204 - No Content
        if (response.status === 204) { // 204 - No Content
          // Find the row index for the active document
          const rowIndex = $docsTable.DataTable().rows().indexes().filter((index) => {
            let rowData = $docsTable.DataTable().row(index).data();
            return rowData.id === activeDoc.id;
          });
          // Check if the row exists, if so, update the row data from outputData
          if (rowIndex.length > 0) {
            // Update the row data
            $docsTable.DataTable().row(rowIndex[0]).data({
              id: outputData.id,
              title: outputData.title,
              created: moment(outputData.created_at).format('MMMM Do YYYY, h:mm:ss a'),
              updated: moment(outputData.updated_at).format('MMMM Do YYYY, h:mm:ss a')
            }).draw();
          }
        } else {
          // Add a new row to DataTable, using the response data
          $docsTable.DataTable().row.add({
            id: data.id,
            title: data.title,
            created: moment(data.created_at).format('MMMM Do YYYY, h:mm:ss a'),
            updated: moment(data.updated_at).format('MMMM Do YYYY, h:mm:ss a')
          }).draw();
        }
      }).catch(function (error) {
        console.log(error);
        Swal.fire({
          title: 'Error!',
          text: 'Saving failed',
          icon: 'error',
        });
      });
    }).catch((error) => {
      console.log('Saving failed: ', error);
      Swal.fire({
        title: 'Error!',
        text: 'Editor saving failed',
        icon: 'error',
      });
    });
  }
}

function convertDataToHtml(blocks) {
  var convertedHtml = "";
  blocks.map(block => {    
    switch (block.type) {
      case "header":
        convertedHtml += `<h${block.data.level}>${block.data.text}</h${block.data.level}>`;
        break;
      case "embded":
        convertedHtml += `<div><iframe width="560" height="315" src="${block.data.embed}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>`;
        break;
      case "paragraph":
        convertedHtml += `<p>${block.data.text}</p>`;
        break;
      case "delimiter":
        convertedHtml += "<hr />";
        break;
      case "image":
        convertedHtml += `<img class="img-fluid" src="${block.data.file.url}" title="${block.data.caption}" /><br /><p>${block.data.caption}</p>`;
        break;
      case "list":
        let lsType = (block.data.style == 'ordered') ? "ol" : "ul";
        convertedHtml += ('<'+lsType+'>');
        block.data.items.forEach(function(li) {
          convertedHtml += `<li>${li.content}</li>`;
        });
        convertedHtml += ('</'+lsType+'>');
        break;
      case "table":
        convertedHtml += '<table>';
        block.data.content.forEach(function(tr) {
          convertedHtml += '<tr>';
          tr.forEach(function(td) {
            convertedHtml += `<td>${td}</td>`;
          });
          convertedHtml += '</tr>';
        });
        convertedHtml += '</table>';
        break;
      default:
        console.log("Unknown block type", block.type);
        break;
    }
  });
  return convertedHtml;
}

function demoFromHTML(source, filename) {
  let pdf = new jsPDF('p', 'pt', 'letter');
  let specialElementHandlers = {
    // element with id of "bypass" - jQuery style selector
    '#bypassme': function (element, renderer) {
      // true = "handled elsewhere, bypass text extraction"
      return true
    }
  };
  let margins = {
    top: 30,
    bottom: 60,
    left: 40,
    width: 522
  };
  pdf.fromHTML(
    source, // HTML string or DOM elem ref.
    margins.left, // x coord
    margins.top, { // y coord
    'width': margins.width, // max width of content on PDF
    'elementHandlers': specialElementHandlers
    },

    function (dispose) {
      pdf.save(filename + '.pdf');
    }, margins
  );
}

function handleExportClick() {
  // get title
  const title = titleInput.value.trim();
  if (!title) {
    Swal.fire({
      title: 'Title?',
      text: 'Please enter a title',
      icon: 'warning',
    })
  } else {    
    editor.save().then((outputData) => {
      // check for content
      if (outputData.blocks.length === 0) {
        Swal.fire({
          title: 'Content?',
          text: 'Please enter some content',
          icon: 'warning',
        })
        return;
      }
      // const edjsParser = edjsHTML();
      // const html = edjsParser.parseStrict(outputData);
      const html = convertDataToHtml(outputData.blocks);
      console.log(outputData.blocks);
      console.log(html);
      demoFromHTML(html, title);
    }).catch((error) => {
      console.log('Saving failed: ', error);
      Swal.fire({
        title: 'Error!',
        text: 'Editor saving failed',
        icon: 'error',
      });
    });
  }
}

// function handlePreviewClick() {
//   editor.save().then((outputData) => {
//     const html = convertDataToHtml(outputData.blocks);
//     console.log(html);
//     $('#preview-modal').modal('show');
//     // document.getElementById('preview').innerHTML = html;
//     // $('#preview').html(html);

//   }).catch((error) => {
//     console.log('Saving failed: ', error);
//     Swal.fire({
//       title: 'Error!',
//       text: 'Editor saving failed',
//       icon: 'error',
//     });
//   });
// }
/*
* Docs table
* - https://datatables.net/
* */

// Initialize dataTables
function initDocsTable(data) {
  console.log('data: ', data)
  let deleteButton = `
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded delete">
            <i class="fa-solid fa-trash-can mr-1"></i>
            Delete
        </button>
    `;
  // Format the data
  const $data = data.map(doc => {
    return {
      id: doc.id,
      title: doc.title,
      created: moment(doc.created_at).format('MMMM Do YYYY, h:mm:ss a'),
      updated: moment(doc.updated_at).format('MMMM Do YYYY, h:mm:ss a'),
    }
  });
  // Initialize DataTables
  $docsTable.DataTable({
    data: $data,
    columns: [
      { data: 'id' },
      { data: 'title' },
      { data: 'created' },
      { data: 'updated' },
      {
        data: null, // This column does not correspond to any data field
        defaultContent: deleteButton, // Delete button
        orderable: false // Disable sorting for this column
      }
    ]
  });

  // Docs table catch delete button click
  $docsTable.on('click', '.delete', function (e) {
    e.stopPropagation();
    const $row = $(this).closest('tr');
    const id = $row.find('td:first-child').text();
    deleteDoc(id).then(res => {
      // Use DataTable API to remove the row
      $docsTable.DataTable().row($row).remove().draw();
      clearEditor();
    }).catch(e => {
      console.log('error: ', e);
      Swal.fire({
        title: 'Error!',
        text: 'Error deleting document',
        icon: 'error',
      });
    });
  });

  // DataTable row click event
  $docsTable.on('click', 'tbody tr', function () {
    // Remove active class from all rows
    $docsTable.find('tbody tr').removeClass('active-row');
    // Add active class to the clicked row
    const $row = $(this);
    $row.addClass('active-row');
    const docId = $row.find('td:first-child').text(); // Assuming first column contains the ID
    // check if editor is empty, if so, skip this
    if ($docsTable.DataTable().row(this).data()) {
      loadDocIntoEditor(docId);
    }
  });
}

// Function to load a doc into Editor.js
function loadDocIntoEditor(docId) {
  // Get the document from the server
  axios.get(`/doc/${docId}`).then(res => {
    const { data } = res.data;
    // set activeDoc
    activeDoc = data;
    // Set the title in the input field
    if (titleInput) {
      titleInput.value = activeDoc.title; // Assuming the title is in docData.title
    }
    // Load new data into Editor.js
    editor.render(activeDoc);
  }).catch(error => {
    console.error('Error: ', error);
    Swal.fire({
      title: 'Error!',
      text: 'Error fetching document',
      icon: 'error',
    });
  });
}

// Get all docs
function getAllDocs() {
  return axios.get('/docs');
}

// Delete doc
function deleteDoc(id) {
  return axios.delete(`/doc/${id}`);
}
