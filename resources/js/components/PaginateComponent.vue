<template>
    <ul :class="containerClass" v-if="!noLiSurround">
        <li v-if="firstLastButton" :class="[pageClass, firstPageSelected() ? disabledClass : '']" @click="selectFirstPage()">
            <a  @keyup.enter="selectFirstPage()" :class="pageLinkClass" :tabindex="firstPageSelected() ? -1 : 0" v-html="firstButtonText"></a>
        </li>

        <li v-if="!(firstPageSelected() && hidePrevNext)" :class="[prevClass, firstPageSelected() ? disabledClass : '']" @click="prevPage()">
            <a  @keyup.enter="prevPage()" :class="prevLinkClass" :tabindex="firstPageSelected() ? -1 : 0" v-html="prevText"></a>
        </li>

        <li v-for="page in pages" :class="[pageClass, page.selected ? activeClass : '', page.disabled ? disabledClass : '', page.breakView ? breakViewClass: '']" @click="handlePageSelected(page.index + 1)">
            <a v-if="page.breakView" :class="[pageLinkClass, breakViewLinkClass]" tabindex="0"><slot name="breakViewContent">{{ breakViewText }}</slot></a>
            <a v-else-if="page.disabled" :class="pageLinkClass" tabindex="0">{{ page.content }}</a>
            <a v-else  @keyup.enter="handlePageSelected(page.index + 1)" :class="pageLinkClass" tabindex="0">{{ page.content }}</a>
        </li>

        <li v-if="!(lastPageSelected() && hidePrevNext)" :class="[nextClass, lastPageSelected() ? disabledClass : '']" @click="nextPage()">
            <a @keyup.enter="nextPage()" :class="nextLinkClass" :tabindex="lastPageSelected() ? -1 : 0" v-html="nextText"></a>
        </li>

        <li v-if="firstLastButton" :class="[pageClass, lastPageSelected() ? disabledClass : '']" @click="selectLastPage()">
            <a @keyup.enter="selectLastPage()" :class="pageLinkClass" :tabindex="lastPageSelected() ? -1 : 0" v-html="lastButtonText"></a>
        </li>
    </ul>

    <div :class="containerClass" v-else>
        <a v-if="firstLastButton" @click="selectFirstPage()" @keyup.enter="selectFirstPage()" :class="[pageLinkClass, firstPageSelected() ? disabledClass : '']" tabindex="0" v-html="firstButtonText"></a>
        <a v-if="!(firstPageSelected() && hidePrevNext)" @click="prevPage()" @keyup.enter="prevPage()" :class="[prevLinkClass, firstPageSelected() ? disabledClass : '']" tabindex="0" v-html="prevText"></a>
        <template v-for="page in pages">
            <a v-if="page.breakView" :class="[pageLinkClass, breakViewLinkClass, page.disabled ? disabledClass : '']" tabindex="0"><slot name="breakViewContent">{{ breakViewText }}</slot></a>
            <a v-else-if="page.disabled" :class="[pageLinkClass, page.selected ? activeClass : '', disabledClass]" tabindex="0">{{ page.content }}</a>
            <a v-else @click="handlePageSelected(page.index + 1)" @keyup.enter="handlePageSelected(page.index + 1)" :class="[pageLinkClass, page.selected ? activeClass : '']" tabindex="0">{{ page.content }}</a>
        </template>
        <a v-if="!(lastPageSelected() && hidePrevNext)" @click="nextPage()" @keyup.enter="nextPage()" :class="[nextLinkClass, lastPageSelected() ? disabledClass : '']" tabindex="0" v-html="nextText"></a>
        <a v-if="firstLastButton" @click="selectLastPage()" @keyup.enter="selectLastPage()" :class="[pageLinkClass, lastPageSelected() ? disabledClass : '']" tabindex="0" v-html="lastButtonText"></a>
    </div>
</template>

<script>
export default {
    props: {
        value: {
            type: Number
        },
        pageCount: {
            type: Number,
            required: true
        },
        forcePage: {
            type: Number
        },
        clickHandler: {
            type: Function,
            default: () => { }
        },
        pageRange: {
            type: Number,
            default: 3
        },
        marginPages: {
            type: Number,
            default: 1
        },
        prevText: {
            type: String,
            default: 'Prev'
        },
        nextText: {
            type: String,
            default: 'Next'
        },
        breakViewText: {
            type: String,
            default: '…'
        },
        containerClass: {
            type: String
        },
        pageClass: {
            type: String
        },
        pageLinkClass: {
            type: String
        },
        prevClass: {
            type: String
        },
        prevLinkClass: {
            type: String
        },
        nextClass: {
            type: String
        },
        nextLinkClass: {
            type: String
        },
        breakViewClass: {
            type: String
        },
        breakViewLinkClass: {
            type: String
        },
        activeClass: {
            type: String,
            default: 'active'
        },
        disabledClass: {
            type: String,
            default: 'disabled'
        },
        noLiSurround: {
            type: Boolean,
            default: false
        },
        firstLastButton: {
            type: Boolean,
            default: false
        },
        firstButtonText: {
            type: String,
            default: 'First'
        },
        lastButtonText: {
            type: String,
            default: 'Last'
        },
        hidePrevNext: {
            type: Boolean,
            default: false
        }
    },
    beforeUpdate() {
        if (this.forcePage === undefined) return
        if (this.forcePage !== this.selected) {
            this.selected = this.forcePage
        }
    },
    computed: {
        selected: {
            get: function() {
                return this.value || this.innerValue
            },
            set: function(newValue) {
                this.innerValue = newValue
            }
        },
        pages: function () {
            let items = {};
            let page = {};
            if (this.pageCount <= this.pageRange) {
                for (let index = 0; index < this.pageCount; index++) {
                    page = {
                        index: index,
                        content: index + 1,
                        selected: index === (this.selected - 1)
                    }
                    items[index] = page
                }
            } else {
                const halfPageRange = Math.floor(this.pageRange / 2)

                let setPageItem = index => {
                    page = {
                        index: index,
                        content: index + 1,
                        selected: index === (this.selected - 1)
                    }

                    items[index] = page
                }

                let setBreakView = index => {
                    let breakView = {
                        disabled: true,
                        breakView: true
                    }

                    items[index] = breakView
                }

                // 1st - loop thru low end of margin pages
                for (let i = 0; i < this.marginPages; i++) {
                    setPageItem(i);
                }

                // 2nd - loop thru selected range
                let selectedRangeLow = 0;
                if (this.selected - halfPageRange > 0) {
                    selectedRangeLow = this.selected - 1 - halfPageRange;
                }

                let selectedRangeHigh = selectedRangeLow + this.pageRange - 1;
                if (selectedRangeHigh >= this.pageCount) {
                    selectedRangeHigh = this.pageCount - 1;
                    selectedRangeLow = selectedRangeHigh - this.pageRange + 1;
                }

                for (let i = selectedRangeLow; i <= selectedRangeHigh && i <= this.pageCount - 1; i++) {
                    setPageItem(i);
                }

                // Check if there is breakView in the left of selected range
                if (selectedRangeLow > this.marginPages) {
                    setBreakView(selectedRangeLow - 1)
                }

                // Check if there is breakView in the right of selected range
                if (selectedRangeHigh + 1 < this.pageCount - this.marginPages) {
                    setBreakView(selectedRangeHigh + 1)
                }

                // 3rd - loop thru high end of margin pages
                for (let i = this.pageCount - 1; i >= this.pageCount - this.marginPages; i--) {
                    setPageItem(i);
                }
            }
            return items
        }
    },
    data() {
        return {
            innerValue: 1,
        }
    },
    methods: {
        handlePageSelected(selected) {
            if (this.selected === selected) return

            this.innerValue = selected
            this.$emit('input', selected)
            this.clickHandler(selected)
        },
        prevPage() {
            if (this.selected <= 1) return

            this.handlePageSelected(this.selected - 1)
        },
        nextPage() {
            if (this.selected >= this.pageCount) return

            this.handlePageSelected(this.selected + 1)
        },
        firstPageSelected() {
            return this.selected === 1
        },
        lastPageSelected() {
            return (this.selected === this.pageCount) || (this.pageCount === 0)
        },
        selectFirstPage() {
            if (this.selected <= 1) return

            this.handlePageSelected(1)
        },
        selectLastPage() {
            if (this.selected >= this.pageCount) return

            this.handlePageSelected(this.pageCount)
        }
    }
}
</script>

<style lang="css" scoped>
a {
    cursor: pointer;
}

.pagination {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 20px 0;
    margin-top: 20px;
}

.page-item {
    margin: 0 5px;
}

.page-item a {
    display: block;
    padding: 5px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.page-item.active a {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.page-item.disabled a {
    color: #6c757d;
    pointer-events: none;
}
.control-button {
    vertical-align: middle;
    margin-top: 5px;
    padding: 0 4px;
}
</style>

